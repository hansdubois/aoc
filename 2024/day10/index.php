<?php
declare(strict_types = 0);

require __DIR__ . '/../common/Coord.php';
require __DIR__ . '/../common/Grid.php';

require __DIR__ . '/../common/Stopwatch.php';

$input = explode("\n", file_get_contents(__DIR__ . '/input.txt'));
$grid = new Grid();

$startPoints = [];
$graph = new Graph();

for($y = 0; $y < count($input); $y++) {
    $chars = str_split($input[$y]);

    for ($x = 0; $x < count($chars); $x++) {
        $coord = new Coord($x, $y);
        $value = intval($chars[$x]);

        $grid->add($coord, $value);

        if ($value === 0) {
            $startPoints[] = (string)$coord . "-" . "0";
        }
    }
}

$directions = [
    UP,
    DOWN,
    LEFT,
    RIGHT
];

for($y = 0; $y < count($input); $y++) {
    $chars = str_split($input[$y]);

    for ($x = 0; $x < count($chars); $x++) {
        $vertex = $x . "-". $y . "-" . $chars[$x];
        $graph->addVertex($vertex);

        /** @var Coord $node */
        $node = new Coord($x, $y);

        foreach ($directions as $direction) {
            $targetPosition = $node->add($direction);

            if ($grid->existsOnGrid($targetPosition)) {
                $targetValue = $grid->get($targetPosition->x, $targetPosition->y);

                if ($targetValue - intval($chars[$x]) === 1) {
                    $targetVertex = (string)$targetPosition . "-" . $targetValue;

                    echo $vertex . "->" . $targetVertex; echo PHP_EOL;
                    echo PHP_EOL;

                    $graph->addEdge($vertex, $targetVertex);
                }
            }
        }

    }
}

$score = 0;
$scoreTwo = 0;

$stopwatch = new Stopwatch();
$stopwatch->start();

foreach ($startPoints as $startPoint) {
    $bfsResult = $graph->findPathsEndingAt($startPoint);
    $ends = [];
    foreach ($bfsResult as $path) {
        $ends[] = $path[9];
    }

    $score += count(array_unique($ends));
    $scoreTwo += count($bfsResult);
}

echo "Part 1: " . $score . PHP_EOL;
echo "Part 2: " . $scoreTwo. PHP_EOL;

echo $stopwatch->ellapsed();

class Graph
{
    private $adjList = [];

    // Add a vertex to the adjacency list
    public function addVertex($vertex)
    {
        if (!array_key_exists($vertex, $this->adjList)) {
            $this->adjList[$vertex] = [];
        }
    }

    // Add an edge between two vertices
    public function addEdge($vertex1, $vertex2)
    {
        $this->adjList[$vertex1][] = $vertex2;
    }

    // BFS traversal
    public function bfs($startVertex)
    {
        $visited = [];
        $queue = [$startVertex]; // Initialize the queue with the starting vertex
        $result = [];
        $done = false;

        while (!empty($queue) && !$done) {
            $vertex = array_shift($queue); // Dequeue a vertex
            $value = explode("-", $vertex)[2];

            if (!isset($visited[$vertex])) {
                $visited[$vertex] = true;
                $result[] = $vertex;

                // Enqueue all unvisited neighbors
                foreach ($this->adjList[$vertex] as $neighbor) {
                    if (!isset($visited[$neighbor])) {
                        $queue[] = $neighbor;
                    }
                }
            }
        }

        return $result;
    }

    // BFS to find all paths ending at a specific node
    public function findPathsEndingAt($startVertex) {
        $paths = []; // Store all paths ending at the target vertex
        $queue = [[$startVertex]]; // Initialize the queue with the starting vertex as a single-element path

        while (!empty($queue)) {
            $path = array_shift($queue); // Dequeue a path
            $currentVertex = end($path); // Get the last vertex in the current path

            $value = explode("-", $currentVertex)[2];

            // If the current vertex is the target, add the path to the result
            if (intval($value) === 9) {
                $paths[] = $path;
            }

            // Explore neighbors
            foreach ($this->adjList[$currentVertex] as $neighbor) {
                if (!in_array($neighbor, $path)) { // Avoid cycles by not revisiting nodes
                    $newPath = $path;
                    $newPath[] = $neighbor; // Extend the current path
                    $queue[] = $newPath; // Enqueue the new path
                }
            }
        }

        return $paths;
    }
}