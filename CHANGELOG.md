# Changelog


## 1.2.0 (2021-10-04)

*   Feature: Simplify usage by supporting new [default loop](https://reactphp.org/event-loop/#loop).
    (#39 by @clue and #44 by @SimonFrings)

    ```php
    // old (still supported)
    $factory = new Clue\React\SQLite\Factory($loop);

    // new (using default loop)
    $factory = new Clue\React\SQLite\Factory();
    ```

*   Feature: Reject null byte in path to SQLite database file.
    (#42 by @SimonFrings)

*   Maintenance: Improve documentation and examples.
    (#38 by @PaulRotmann and #43 by @SimonFrings)

## 1.1.0 (2020-12-15)

*   Improve test suite and add `.gitattributes` to exclude dev files from exports.
    Add PHP 8 support, update to PHPUnit 9 and simplify test setup.
    (#30, #31, #33, #34 and #37 by @SimonFrings)

## 1.0.1 (2019-05-17)

*   Fix: Fix result set rows for DDL queries to be null, instead of empty array.
    (#26 by @clue)

## 1.0.0 (2019-05-14)

*   First stable release, following SemVer
