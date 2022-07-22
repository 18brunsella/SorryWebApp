CREATE OR REPLACE TABLE p2_user
(
    id       INT(11)      NOT NULL AUTO_INCREMENT,
    email    VARCHAR(255) NOT NULL,
    username VARCHAR(20)  NOT NULL UNIQUE,
    password VARCHAR(64)  NOT NULL,
    salt     VARCHAR(32)  NOT NULL,
    PRIMARY KEY (id),
    UNIQUE INDEX (email)
);

CREATE OR REPLACE TABLE test_p2_user
(
    id       INT(11)      NOT NULL AUTO_INCREMENT,
    email    VARCHAR(255) NOT NULL,
    username VARCHAR(20)  NOT NULL UNIQUE,
    password VARCHAR(64)  NOT NULL,
    salt     VARCHAR(32)  NOT NULL,
    PRIMARY KEY (id),
    UNIQUE INDEX (email)
);
