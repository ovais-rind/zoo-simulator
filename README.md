Zoo Simulator (PHP)

A simple zoo simulator written in plain PHP.
The zoo contains Monkeys, Giraffes, and Elephants. Each animal has health that decreases over time and can be restored by feeding. Different animals have different rules for survival.

Requirements

PHP 7.4+ (or any newer version)

Web server (Apache, Nginx, or PHP’s built-in server)

No frameworks or external libraries are used.

How to Run

Clone the repository:

git clone https://github.com/ovais-rind/zoo-simulator.git
cd zoo-simulator


Start a local PHP server:

php -S localhost:8000


Open the simulator in your browser:
http://localhost:8000/index.php

Features

Starts with 5 Monkeys, 5 Giraffes, 5 Elephants (all at 100% health).

Each hour:

Animals lose a random 0–20% of their current health.

Special rules:

Monkey dies if health < 30%

Giraffe dies if health < 50%

Elephant:

If < 70%, cannot walk (warning)

If still < 70% after next hour → dies

Feeding the zoo:

Increases health by a random 10–25% of current health.

Health capped at 100%.

UI Buttons:

+1 Hour → advance simulation by one hour

Notes

State is preserved using PHP sessions, so the zoo continues between actions.

Code is structured with a base Animal class and specific classes for Monkey, Giraffe, and Elephant.

The design makes it easy to add new animal types with their own health rules.

Feed Zoo → restore animals’ health

Reset → start over
