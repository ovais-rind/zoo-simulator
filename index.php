<?php
// Base Animal class
abstract class Animal {
    public $health = 100;
    public $alive = true;

    public function __construct() {
        $this->health = 100;
        $this->alive = true;
    }

    public function tickHour() {
        if (!$this->alive) return;
        $loss = rand(0, 20);
        $this->health = max(0, $this->health - ($this->health * $loss / 100));
        $this->applyRules();
    }

    public function feed() {
        if (!$this->alive) return;
        $gain = rand(10, 25);
        $this->health = min(100, $this->health + ($this->health * $gain / 100));
        $this->applyRules();
    }

    abstract protected function applyRules();
}

// Monkey
class Monkey extends Animal {
    protected function applyRules() {
        if ($this->alive && $this->health < 30) {
            $this->alive = false;
        }
    }
}

// Giraffe
class Giraffe extends Animal {
    protected function applyRules() {
        if ($this->alive && $this->health < 50) {
            $this->alive = false;
        }
    }
}

// Elephant
class Elephant extends Animal {
    public $cannotWalk = false;
    private $warned = false;

    protected function applyRules() {
        if (!$this->alive) return;
        if ($this->health < 70) {
            if ($this->warned) {
                $this->alive = false;
            } else {
                $this->cannotWalk = true;
                $this->warned = true;
            }
        } else {
            $this->cannotWalk = false;
            $this->warned = false;
        }
    }
}

// Zoo class
class Zoo {
    public $animals = [];
    public $hour = 0;

    public function __construct() {
        $this->reset();
    }

    public function reset() {
        $this->animals = [];
        $this->hour = 0;
        for ($i=0; $i<5; $i++) {
            $this->animals['monkeys'][] = new Monkey();
            $this->animals['giraffes'][] = new Giraffe();
            $this->animals['elephants'][] = new Elephant();
        }
    }

    public function tickHour() {
        $this->hour++;
        foreach ($this->animals as $group) {
            foreach ($group as $animal) {
                $animal->tickHour();
            }
        }
    }

    public function feedAll() {
        foreach ($this->animals as $group) {
            foreach ($group as $animal) {
                $animal->feed();
            }
        }
    }
}

// --- SESSION to keep zoo state ---
session_start();
if (!isset($_SESSION['zoo'])) {
    $_SESSION['zoo'] = new Zoo();
}
$zoo = $_SESSION['zoo'];

// Handle actions
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'tick') $zoo->tickHour();
    if ($_POST['action'] == 'feed') $zoo->feedAll();
    if ($_POST['action'] == 'reset') { $zoo = new Zoo(); $_SESSION['zoo'] = $zoo; }
    $_SESSION['zoo'] = $zoo;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Zoo Simulator</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .dead { color: red; }
        .warn { color: orange; }
    </style>
</head>
<body>
    <h1>Zoo Simulator (PHP)</h1>
    <p>Hour: <?= $zoo->hour ?></p>
    <form method="post">
        <button name="action" value="tick">+1 Hour</button>
        <button name="action" value="feed">Feed Zoo</button>
        <button name="action" value="reset">Reset</button>
    </form>

    <h2>Animals</h2>
    <?php foreach ($zoo->animals as $type => $list): ?>
        <h3><?= ucfirst($type) ?></h3>
        <ul>
            <?php foreach ($list as $i => $a): ?>
                <?php
                  $status = $a->alive ? "Alive" : "Dead";
                  if ($a instanceof Elephant && $a->alive && $a->cannotWalk) $status = "Cannot Walk";
                  $class = !$a->alive ? "dead" : ($status=="Cannot Walk"?"warn":"");
                ?>
                <li class="<?= $class ?>">
                    <?= ucfirst($type)." ".($i+1) ?>: <?= round($a->health,2) ?>% (<?= $status ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
</body>
</html>
