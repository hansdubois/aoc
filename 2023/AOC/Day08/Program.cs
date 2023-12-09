var mode = "real";

var instructions = File.ReadAllLines("input/" + mode + "/instructions.txt").First().ToCharArray().Select(x => x.ToString()).ToList();
var nodeInput = File.ReadAllLines("input/" + mode + "/nodes.txt");

var nodes = new Dictionary<string, Node>();

foreach (var input in nodeInput)
{
    var splitOnEqual = input.Split("=");

    var nodeId = splitOnEqual[0].Trim();
    var splitOnComma = splitOnEqual[1].Replace("(", "").Replace(")", "").Split(", ");
    
    nodes.Add(nodeId, new Node(nodeId, splitOnComma[0].Trim(), splitOnComma[1].Trim()));
}

// Part one
Console.WriteLine("Part 1: " + getSteps("ZZZ", "AAA", instructions, nodes));

// Part Two
Console.WriteLine("Part 2: Find LCM on : https://www.calculatorsoup.com/calculators/math/lcm.php for: ");
var steps = nodes.Where(node => node.Key.EndsWith("A"))
    .Select(node => node.Key)
    .ToList()
    .Select(x => getSteps("Z" ,x, instructions, nodes));

foreach (var step in steps)
{
    Console.WriteLine(step);
}

int getSteps(string endsWith, string start, List<string> instructions, Dictionary<string, Node> nodes)
{
    var currentNode = start;
    var steps = 0;
    var instructionCount = 0;
    
    while (!currentNode.EndsWith(endsWith))
    {
        var direction = instructions[instructionCount];
        currentNode = (direction == "L") ? nodes[currentNode].Left : nodes[currentNode].Right;
    
        instructionCount++;
    
        // Reset instruction count
        if (instructionCount == instructions.Count)
        {
            instructionCount = 0;
        }
    
        steps++;
    }

    return steps;
}

internal class Node
{
    public string Id { get; }
    public string Left { get; }
    public string Right { get; }
    
    public Node(string id, string left, string right)
    {
        Id = id;
        Left = left;
        Right = right;
    }
}