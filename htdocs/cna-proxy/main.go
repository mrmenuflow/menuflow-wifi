// cna-proxy: server for bypass Apple CNA

// Web server handle only path "/hotspot-*.*"
// and returns redirect HTML on first client
// income, on second and other returns empty HTML

package main

import (
	"io/ioutil"
	"net"
	"time"

	"github.com/kelseyhightower/envconfig"
	"github.com/labstack/echo"
	"github.com/labstack/echo/middleware"
	"github.com/patrickmn/go-cache"
)

// environment contains default config from environment
type environment struct {
	// serve address (host:port)
	ServeAddr string `envconfig:"SERVE_ADDR" default:":9998"`
	// redirect html path on disc
	RedirectHTML string `envconfig:"REDIRECT_HTML" default:""`
	// cache timeout to store client data
	CacheTimeout time.Duration `envconfig:"CACHE_TIMEOUT" default:"10s"`
}

// defaultRedirectHTML return redirection (with delayed for 500 ms) to wifi.menuflow.com
var defaultRedirectHTML = []byte(`
<HTML>
<HEAD>
<meta charset="UTF-8">
<TITLE>Success</TITLE>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<link rel="icon" href="data:;base64,iVBORw0KGgo=">
<link rel="stylesheet" href="https://cdn.menuflow.com/app/mnu/bootstrap.5.2.3.min.css">
<link rel="stylesheet" href="https://cdn.menuflow.com/app/mnu/swiper-bundle.min.8.3.0.css">
<link rel="stylesheet" href="https://use.typekit.net/ngt4nfe.css">
<style>
	body {
		height: 100%;
		color: #746A60;
		font-family: "ltc-caslon-pro", serif;
		font-weight: 400;
		background: #efded7;
		background: url(https://wifi.menuflow.dev/inc/la-maison-ani-backdrop.png) center center;
	}
	.link {
		color: #746A60;
	}
	.link:hover {
		color: #59524a;
	}
	.launch-screen {
		overflow: hidden;
		display: flex;
		align-items: center;
		justify-content: center;
		position: fixed;
		top: 0;
		right: 0;
		left: 0;
		bottom: 0;
		height: 100%;
		font-family: Manrope, sans-serif;
		font-size: 13px;
		line-height: 20px;
		color: #746A60;
		background-color: transparent;
		background: url(https://wifi.menuflow.dev/inc/la-maison-ani-backdrop.png) center center;
		background-repeat: no-repeat;
		background-size: cover;
	}
	.privacy, .privacy.on, #opt_in, #opt_out { 
		color: #fff; 
		background: #000; 
		padding: 1rem 1rem;
		border-radius: 7.5px;
		font-family: "ltc-caslon-pro", serif;
		font-style: italic;
		font-size: 21px;
		font-weight: 600;
		line-height: 13px;
		letter-spacing: -1px;
		text-transform: lowercase;
		color: #FFF;
		height: 52px;
		max-height: 52px;
	}
	.privacy { color: #FFF; border: 1px solid ; background: #FFF; text-transform: normal!important; }

	h2 { 
		color: #746A60;
		font-family: "ltc-caslon-pro", serif;
		font-weight: 700;
		font-style: normal;
		font-size: 1.688rem;
		line-height: 1.35;
		margin-bottom: 8px; 
		text-transform: lowercase;
		letter-spacing: -1px;
	}
</style>
</HEAD>
<body>
<div class="launch-screen">
	<div class="offcanvas offcanvas-bottom m-0" style="border: 3px  solid #e6d5cd!important;border-radius:18.5px;height:225px;background: rgba(239, 223, 218, 0.75);" data-bs-backdrop="false" tabindex="-1" id="privacy" aria-labelledby="offcanvasBottomLabel">
		<div class="offcanvas-body">
			<div class="w-100 p-2 pb-0">
					<h2>Connecting...</h2>
					<p class="mt-3">Click the button below to access WiFi.</p>
			</div>	
		</div>
		<div class="offcanvas-footer p-3 pt-0 pb-4">
			<a href="https://wifi.menuflow.dev/wifi" class="w-100 btn btn-lg mt-3 privacy on connect" aria-label="Close" id="ok" style="border-color: #BC9D95;background:#BC9D95">
				Connect...
			</a>  
		</div>
	</div>
</div>
<script src="https://cdn.menuflow.com/app/mnu/jquery-3.4.1.min.js"></script>
<script src="https://cdn.menuflow.com/app/mnu/bootstrap.bundle.5.2.3.min.js"></script>
<script>

		var as_launch  = new bootstrap.Offcanvas('#privacy');
		as_launch.show(); 
		
		$().trigger('click')
		
     function addElement(absoluteurl)
     {
        var anchor = document.createElement('a');
        anchor.href = absoluteurl;
        window.document.body.insertBefore(anchor, window.document.body.firstChild);
        setTimeout(function(){ anchor.click(); }, 500);
        // document.body.appendChild(elemDiv); // appends last of that element
    }
    addElement("https://wifi.menuflow.dev/wifi");
</script>
</body>
</HTML>
`)

func main() {
	var env environment
	var err = envconfig.Process("", &env)
	if err != nil {
		panic(err)
	}

	redirectHTMLbyte, err := ioutil.ReadFile(env.RedirectHTML)
	if err != nil || len(redirectHTMLbyte) == 0 {
		redirectHTMLbyte = defaultRedirectHTML
	}

	var redirectHTML = string(redirectHTMLbyte)

	// cache initialization
	var ch = cache.New(env.CacheTimeout, env.CacheTimeout)

	// router initialization
	var e = echo.New()
	e.HideBanner = true

	// include standard recover and logger for router
	e.Use(middleware.Recover())
	e.Use(middleware.Logger())

	// set handler for Apple CNA common internet detection URI
	e.GET("/hotspot-*.*",
		func(c echo.Context) error {
			var clientID, _, _ = net.SplitHostPort(c.Request().RemoteAddr)
			if _, found := ch.Get(clientID); found {
				return c.HTML(200, string(redirectHTML))
			}
			ch.Set(clientID, true, cache.DefaultExpiration)
			return c.HTML(200, "<html></html>")
		},
	)

	// start server
	if err = e.Start(env.ServeAddr); err != nil {
		panic(err.Error())
	}
}
