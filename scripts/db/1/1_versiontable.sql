CREATE TABLE db_schema_versions (
    `version` INT NOT NULL PRIMARY KEY,
    `created` DATETIME DEFAULT CURRENT_TIMESTAMP
)