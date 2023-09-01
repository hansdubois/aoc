// See https://aka.ms/new-console-template for more information

using System.Text.RegularExpressions;

var count = 0;

var input = File.ReadLines("input/input.txt");

foreach (var triangleInput in input)
{
    var matches = Regex.Matches(triangleInput, @"\d+").Select(m => int.Parse(m.Value)).ToArray();
    var triangle = new Triangle(matches[0], matches[1], matches[2]);

    if (triangle.IsValidTriangle())
    {
        count++;
    } 
}

Console.WriteLine("Possible triangles: " + count);

var tripleCount = 0;

var partTwoInput = File.ReadAllLines("input/input.txt").Chunk(3);

foreach (var trianglesInput in partTwoInput)
{
    var joined = string.Join(" ", trianglesInput);
    var matches = Regex.Matches(joined, @"\d+").Select(m => int.Parse(m.Value)).ToArray();

    var triangles = new List<Triangle>();
    triangles.Add(new Triangle(matches[0], matches[3], matches[6]));
    triangles.Add(new Triangle(matches[1], matches[4], matches[7]));
    triangles.Add(new Triangle(matches[2], matches[5], matches[8]));

    foreach (var triangle in triangles)
    {
        if (triangle.IsValidTriangle())
        {
            tripleCount++;
        }
    }
}

Console.WriteLine("Part 2 triangles: " + tripleCount);

class Triangle
{
    private readonly int _first;
    private readonly int _second;
    private readonly int _third;

    public Triangle(int first, int second, int third)
    {
        _first = first;
        _second = second;
        _third = third;
    }

    public bool IsValidTriangle()
    {
        return this._first + this._second > this._third &&
               this._second + this._third > this._first &&
               this._first + this._third > this._second;
    }
}