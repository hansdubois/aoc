using System.Text.RegularExpressions;

var regex = new Regex(@"Sensor at x=(?<SensorX>-?\d+), y=(?<SensorY>-?\d+): closest beacon is at x=(?<BeaconX>-?\d+), y=(?<BeaconY>-?\d+)");
var grid = new Dictionary<int, Dictionary<int, string>>();
 
var points = File.ReadAllLines("input/sample.txt").Select(line => regex.Match(line)).Select((matches) =>
{
    var beacon = new Coordinate()
        { x = Int32.Parse(matches.Groups["BeaconX"].Value), y = Int32.Parse(matches.Groups["BeaconY"].Value) };
    var sensor = new Coordinate()
        { x = Int32.Parse(matches.Groups["SensorX"].Value), y = Int32.Parse(matches.Groups["SensorY"].Value) };

    var point = new Point()
    {
        beacon = beacon,
        sensor = sensor
    };

    point.addCoordinatesToGrid(grid);
    
    return point;
}).ToList();


var top = grid.Keys.Min();
var bottom = grid.Keys.Max();
var farLeft = Int32.MaxValue;
var farRight = Int32.MinValue;
;

foreach (var row in grid)
{
    foreach (var column in row.Value)
    {
        if (column.Key < farLeft)
            farLeft = column.Key;
        
        if (column.Key > farRight)
            farRight = column.Key;

    }
}

Draw();

Console.WriteLine("Items where no beacon can be placed: {0}", grid[10].Values.ToList().FindAll(x => x == "~").Count);


void Draw()
{
    for (var y = top; y <= bottom + 15; y++)
    {
        for (var x = farLeft; x < farRight; x++)
        {
            if (!grid.ContainsKey(y) || !grid[y].ContainsKey(x))
            {
                Console.Write("  ");
            }
            else
            {
                Console.Write(grid[y][x]);
            }
        }
        
        Console.WriteLine();
    }
}

class Point
{
    public Coordinate sensor { get; set; }
    public Coordinate beacon { get; set; }

    public int GetDistance()
    {
        return Math.Abs(sensor.x - beacon.x) + Math.Abs(sensor.y - beacon.y);
    }

    public void addCoordinatesToGrid(Dictionary<int, Dictionary<int, string>> grid)
    {
        setToGrid(grid, sensor.x, sensor.y, "🚨");
        setToGrid(grid, beacon.x, beacon.y, "🥓");

        var drawReach = GetDistance();
        var drawTop = drawReach;
        var drawBottom = drawReach;
      
        for (var x = sensor.x - drawReach; x < sensor.x + drawReach; x++)
        {
            setToGrid(grid, (int)x, sensor.y, "🌊");
        }
        
        var iteration = 1;
        while (iteration <= drawReach)
        {
            var itemsToDraw = drawTop * 2 - 1;
            var pointToStart = sensor.x - drawTop + 1;
        
            for (var x = pointToStart; x < pointToStart + itemsToDraw; x++)
            {
                setToGrid(grid, (int)x, sensor.y  + (iteration * -1), "🌊");
            }

            iteration++;

            drawTop -= 1;
        }
        
        var downIteration = 1;
        while (downIteration <= drawReach)
        {
            var itemsToDraw = drawBottom * 2 - 1;
            var pointToStart = sensor.x - drawBottom + 1;
        
            for (var x = pointToStart; x < pointToStart + itemsToDraw; x++)
            {
                setToGrid(grid, (int)x, sensor.y  + downIteration, "🌊");
            }

            downIteration++;

            drawBottom -= 1;
        }
    }

    private void setToGrid(Dictionary<int, Dictionary<int, string>> grid, int x, int y, string value)
    {
        if (!grid.ContainsKey(y))
            grid.Add(y, new Dictionary<int, string>());

        if (!grid[y].ContainsKey(x))
        {
            grid[y].Add(x,value);
        } else if (grid[y][x] == "🌊")
        {
            grid[y][x] = value;
        }
    }
}

class Coordinate
{
    public int x { get; set; }
    public int y { get; set; }
}
