using System.Linq.Expressions;

var mode = "sample";

var gridInput = File.ReadAllLines("input/" + mode + ".txt");
var galaxies = new List<Coord>();

var horizonsToExpand = new List<int>();
var verticalsToExpand = new List<int>();

for(var y = 0; y < gridInput.Length; y++)
{
    if (gridInput[y].ToCharArray().GroupBy(x => x).Count() == 1)
    {
        horizonsToExpand.Add(y);
    }
}

// Find blank vertical lines
for (var x = 0; x < gridInput.First().Length; x++)
{
    var chars = new List<char>();

    for (var y = 0; y < gridInput.Length; y++)
    {
        chars.Add(gridInput[y][x]);
    }

    var groups = chars.GroupBy(x => x);
    
    if (groups.Count() == 1)
    {
        verticalsToExpand.Add(x);
    }
}

var expander = 999999;

for (var y = 0; y < gridInput.Length; y++)
{
    for (var x = 0; x < gridInput[y].Length; x++)
    {
        if (gridInput[y][x].ToString() == "#")
        {
            var addToX = verticalsToExpand.Count(xVert => xVert < x) * expander;
            var addToY = horizonsToExpand.Count(yHor => yHor < y) * expander;

            var galaxy = new Coord() { X = (x + addToX), Y = y + addToY, Id = galaxies.Count() };
            
            galaxies.Add(galaxy);
        }
    }
}

var distances = new List<long>();


for (var galaxyNr = 0; galaxyNr < galaxies.Count; galaxyNr++)
{
    var source = galaxies.Where(x => x.Id == galaxyNr).First();
    var targets = galaxies.Where(x => x.Id > galaxyNr).ToList();

    foreach (var target in targets)
    {
        var distance = Math.Abs(source.X - target.X) + Math.Abs(source.Y - target.Y);
        
        distances.Add(distance);
    }
}

Console.WriteLine(distances.Sum());

internal struct Coord
{
    public int X { get; set; }
    public int Y { get; set; }
    public int Id { get; set; }

    public override string ToString()
    {
        return Id + ": " + Y + "-" + X;
    }
}
