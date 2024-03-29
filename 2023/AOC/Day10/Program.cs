﻿using System.Drawing;
using System.Numerics;

var mode = "sample";
var gridInput = File.ReadAllLines("input/" + mode + ".txt");

var nodeTypes = new Dictionary<string, NodeType>()
{
    { "S", NodeType.Start},
    { "|", NodeType.VerticalPipe},
    { "-", NodeType.HorizontalPipe},
    { "L", NodeType.LPipe},
    { "J", NodeType.JPipe},
    { "7", NodeType.SevenPipe},
    { "F", NodeType.FPipe},
    { ".", NodeType.Ground},
};

var graph = new Graph();
Node startingNode = null;

var allPoints = new List<PointF>();

for(var y = 0; y < gridInput.Length; y++) {
    for (var x = 0; x < gridInput[y].Length; x++)
    {
        var nodeType = nodeTypes[gridInput[y][x].ToString()];
        var node = new Node(nodeType, new Coord() { X = x, Y = y }, gridInput[y][x].ToString());

        if (nodeType == NodeType.Start)
        {
            startingNode = node;
        }
        
        allPoints.Add(new PointF(new Vector2(x, y)));
        graph.AddNode(node);
    }
}

var visited = new List<string>();
var paths = new List<string>();

findFurthestNode(startingNode, visited);

var polygon = new List<PointF>();

foreach (var point in visited)
{
    var pointParts = point.Split("-").Select(float.Parse).ToList();
    
    polygon.Add(new PointF(new Vector2(pointParts[1], pointParts[0])));
}


var counter = 0;
var PointsInPolyGon = new List<string>();
foreach (var point in allPoints)
{
    if (IsPointInPolygon4(polygon, point) && !visited.Contains(point.Y + "-" + point.X))
    {
        PointsInPolyGon.Add(point.Y + "-" + point.X);
        counter++;
    }
}

Console.WriteLine("Counter" + counter);

var longest = paths.OrderBy(x => x.Length).Last();

Console.WriteLine(longest.Count( x => x.Equals('/')));

printGraph(graph);

void findFurthestNode(Node node, List<string> visited)
{
    var queue = new Queue<PathedNode>();
    
    queue.Enqueue(new PathedNode(node.ToString(),  node));
    
    while (queue.Count > 0)
    {
        var currentNode = queue.Dequeue();

        if (!visited.Contains(currentNode.node.ToString()))
        {
            visited.Add(currentNode.node.ToString());
            paths.Add(currentNode.path);
            
            foreach (var nodeToVisit in graph.getAdjecendNodesForNode(currentNode.node))
            {
                queue.Enqueue(new PathedNode(currentNode.path + " / " + nodeToVisit.ToString(), nodeToVisit));
            }
        }
    }
}

/// <summary>
/// Determines if the given point is inside the polygon
/// </summary>
/// <param name="polygon">the vertices of polygon</param>
/// <param name="testPoint">the given point</param>
/// <returns>true if the point is inside the polygon; otherwise, false</returns>
static bool IsPointInPolygon4(List<PointF> poly, PointF p)
{
    PointF p1, p2;
    bool inside = false;

    if (poly.Count < 3)
    {
        return inside;
    }

    var oldPoint = new PointF(
        poly[poly.Count - 1].X, poly[poly.Count - 1].Y);

    for (int i = 0; i < poly.Count; i++)
    {
        var newPoint = new PointF(poly[i].X, poly[i].Y);

        if (newPoint.X > oldPoint.X)
        {
            p1 = oldPoint;
            p2 = newPoint;
        }
        else
        {
            p1 = newPoint;
            p2 = oldPoint;
        }

        if ((newPoint.X < p.X) == (p.X <= oldPoint.X)
            && (p.Y - (long) p1.Y)*(p2.X - p1.X)
            < (p2.Y - (long) p1.Y)*(p.X - p1.X))
        {
            inside = !inside;
        }

        oldPoint = newPoint;
    }

    return inside;
}

void printGraph(Graph graph)
{
    for(var y = 0; y < graph.GetHeight(); y++) {
        for (var x = 0; x < graph.GetWidth(); x++)
        {
            var node = graph.GetNode(x, y);

            if (PointsInPolyGon.Contains(y + "-" + x))
            {
                Console.BackgroundColor = ConsoleColor.Blue;
                Console.ForegroundColor = ConsoleColor.White;
                Console.Write("X");
                Console.ResetColor();
                
            } else if (visited.Contains(y + "-" + x))
            {
                Console.Write("X");   
            }
            else
            {
                Console.Write(nodeTypes.Where(x => x.Value == node.NodeType).Select(x => x.Key).First());
            }
        }
        
        Console.WriteLine();
    }
}


enum NodeType
{
    Start = 1, 
    VerticalPipe = 2,
    HorizontalPipe = 3,
    LPipe = 4,
    JPipe = 5, 
    SevenPipe = 6,
    FPipe = 7, 
    Ground = 8
}

class Graph
{
    private Dictionary<int, Dictionary<int, Node>> graph = new();

    public void AddNode(Node node)
    {
        if (!graph.ContainsKey(node.Coord.Y))
        {
            this.graph.Add(node.Coord.Y, new Dictionary<int, Node>() { {node.Coord.X, node} });
        }
        else
        {
            this.graph[node.Coord.Y].Add(node.Coord.X, node);    
        }
    }

    public List<Node> getAdjecendNodesForNode(Node node)
    {
        var Nodelist = new List<Node>();

        if (node.NodeType ==  NodeType.Ground)
        {
            return Nodelist;
        }

        var neighbours = neighbourLookup[node.NodeType];
        
        foreach (var neighbour in neighbours)
        {
            var coordToGet = new Coord() { X = node.Coord.X + neighbour.X, Y = node.Coord.Y + neighbour.Y };

            if (this.graph.ContainsKey(coordToGet.Y) 
                && this.graph[coordToGet.Y].ContainsKey(coordToGet.X) 
                && this.graph[coordToGet.Y][coordToGet.X].NodeType != NodeType.Ground)
            {
                Nodelist.Add(this.graph[coordToGet.Y][coordToGet.X]);
            }
        }

        return Nodelist;
    }
    
    public Node GetNode(int x, int y)
    {
        return this.graph[y][x];
    }

    public int GetWidth()
    {
        return this.graph[0].Count();
    }

    public int GetHeight()
    {
        return this.graph.Count();
    }
    
    
    private readonly Dictionary<NodeType, List<Coord>> neighbourLookup = new()
    {
        { NodeType.Start, new List<Coord>()
            {
                new() { X = 0, Y = -1}, // Top
                new() { X = -1, Y = 0}, // Left
            }
        },
        { NodeType.VerticalPipe, new List<Coord>()
            {
                new() { X = 0, Y = -1}, // Top
                new() { X = 0, Y = 1}, // Bottom
            }
        },
        { NodeType.HorizontalPipe, new List<Coord>()
            {
                new() { X = -1, Y = 0}, // Left
                new() { X = 1, Y = 0}, // Right
            }
        },
        { NodeType.LPipe, new List<Coord>()
            {
                new() { X = 0, Y = -1}, // Top
                new() { X = 1, Y = 0}, // Right
            }
        },
        { NodeType.JPipe, new List<Coord>()
            {
                new() { X = 0, Y = -1}, // Top
                new() { X = -1, Y = 0}, // Left
            }
        },
        { NodeType.SevenPipe, new List<Coord>()
            {
                new() { X = -1, Y = 0}, // Left
                new() { X = 0, Y = 1}, // Bottom
            }
        },
        { NodeType.FPipe, new List<Coord>()
            {
                new() { X = 1, Y = 0}, // Right
                new() { X = 0, Y = 1}, // Bottom
            }
        }
    };
}

class Node
{

    public Coord Coord { get; }

    public NodeType NodeType { get; }
    
    public string character { get; }
    
    public Node(NodeType type, Coord coord, string character)
    {
        this.NodeType = type;
        this.Coord = coord;
        this.character = character;
    }

    public override string ToString()
    {
        return Coord.ToString();
    }
}

internal struct Coord
{
    public int X { get; set; }
    public int Y { get; set; }

    public override string ToString()
    {
        return Y + "-" + X;
    }
}

internal class PathedNode
{
    public string path { get; set; }
    public Node node { get; set; }

    public PathedNode(string path, Node node)
    {
        this.path = path;
        this.node = node;
    }
}