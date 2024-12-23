<?php
// Parse input into adjacency list
$lines = explode(PHP_EOL, file_get_contents(__DIR__ . '/input.txt'));
$graph = [];

foreach ($lines as $line) {
    [$source, $target] = explode('-', $line);
    $graph[$source][] = $target;
    $graph[$target][] = $source;
}

foreach ($graph as $node => $neighbors) {
    $graph[$node] = array_unique($neighbors);
}

function bronKerbosch($graph, $currentClique, $potentialNodes, $excludedNodes, &$maximalCliques) {
    if (empty($potentialNodes) && empty($excludedNodes)) {
        $maximalCliques[] = $currentClique;

        return;
    }

    foreach ($potentialNodes as $node) {
        $neighbors = $graph[$node] ?? [];
        bronKerbosch(
            $graph,
            array_merge($currentClique, [$node]), // Expand the current clique
            array_intersect($potentialNodes, $neighbors),  // Filter potential nodes to only include neighbors
            array_intersect($excludedNodes, $neighbors),  // Filter excluded nodes to only include neighbors
            $maximalCliques
        );

        $potentialNodes = array_diff($potentialNodes, [$node]);  // Remove the processed node
        $excludedNodes = array_merge($excludedNodes, [$node]);  // Add it to excluded nodes
    }
}

// Find maximal cliques
$cliques = [];
$allNodes = array_keys($graph);

bronKerbosch($graph, [], $allNodes, [], $cliques);

// Find the largest clique
$largestClique = [];
foreach ($cliques as $clique) {
    if (count($clique) > count($largestClique)) {
        $largestClique = $clique;
    }
}

sort($largestClique);

// Output the result
echo "Largest Clique: " . implode(",", $largestClique) . "\n";

?>