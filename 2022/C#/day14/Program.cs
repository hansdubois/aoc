var grainStart = new Coordinate() { x = 500, y = 0 };

var grid = new Dictionary<int, Dictionary<int, string>>();

var lines = File.ReadAllLines("input/sample.txt");

var mostLeft = Int32.MaxValue;
var mostRight = Int32.MinValue;
var top = Int32.MaxValue;
var bottom = Int32.MinValue;

foreach (var line in lines)
{
    var parts = line.Split(" -> ");
    
    for (int i = 0; i < parts.Length -1; i++)
    {
        var from = Coordinate.fromString(parts[i]);
        var to = Coordinate.fromString(parts[i + 1]);

        foreach (var coordinate in from.createCoordinatesBetween(to))
        {
            if (coordinate.x < mostLeft)
                mostLeft = coordinate.x;
            
            if (mostRight < coordinate.x)
                mostRight = coordinate.x;

            if (coordinate.y < top)
                top = coordinate.y;
            
            if (bottom < coordinate.y)
                bottom = coordinate.y;

            if (!grid.ContainsKey(coordinate.y))
            {
                grid.Add(coordinate.y, new Dictionary<int, string>(){{coordinate.x, Constants.stone}});    
            }
            else if (!grid[coordinate.y].ContainsKey(coordinate.x))
            {
                grid[coordinate.y].Add(coordinate.x, Constants.stone);
            }
        }
    }
}

grid.Add(grainStart.y -1, new Dictionary<int, string>(){{grainStart.x, Constants.start}});
grid.Add(bottom + 2, new Dictionary<int, string>());

for (var i = mostLeft  - 10 ; i <= mostRight + 10; i++)
{
    grid[bottom+2].Add(i, Constants.stone);
}

void Draw()
{
    Console.Clear();
    
    var padding = 10;
    
    for (var y = top - (padding / 2); y <= bottom + (padding /2); y++)
    {
        for (var x = mostLeft - padding; x <= mostRight + padding; x++)
        {
            if (grid.ContainsKey(y) && grid[y].ContainsKey(x))
            {
                Console.Write(grid[y][x]);
            }
            else
            {
                Console.Write(Constants.air);
            }
        }
    
        Console.WriteLine();
    }
}

Coordinate currentSandGrain = new Coordinate() { x = 500, y = 0 };;
var overflows = false;
var grains = 0;

while (!overflows)
{
    //Draw();

    if (currentSandGrain.y > bottom)
    {
        Console.WriteLine("Wooops overflow, took {0} grains", grains);
    }

    // Move down
    if (isFreeSpace(currentSandGrain.x, currentSandGrain.y + 1))
    {
        setSpace(currentSandGrain.x, currentSandGrain.y, Constants.air);
        setSpace(currentSandGrain.x, currentSandGrain.y +1, Constants.sand);
        
        currentSandGrain.y += 1;
    } else if (isFreeSpace(currentSandGrain.x - 1, currentSandGrain.y + 1))
    {
        // left down
        setSpace(currentSandGrain.x, currentSandGrain.y, Constants.air);
        setSpace(currentSandGrain.x -1, currentSandGrain.y +1, Constants.sand);
        
        currentSandGrain.y += 1;
        currentSandGrain.x -= 1;
    } else if(isFreeSpace(currentSandGrain.x + 1, currentSandGrain.y + 1))
    {
        setSpace(currentSandGrain.x, currentSandGrain.y, Constants.air);
        setSpace(currentSandGrain.x +1, currentSandGrain.y +1, Constants.sand);

        currentSandGrain.y += 1;
        currentSandGrain.x += 1;
    }
    else
    {
        if (currentSandGrain.x == 500 && currentSandGrain.y == 0)
        {
            overflows = true;
            
            Console.WriteLine("We reached the end! {0}", grains +1);
        }
        else
        {
            currentSandGrain = new Coordinate() { x = 500, y = 0 };
            grains++;    
        }
    }
    
    Thread.Sleep(20);
} 

bool isFreeSpace(int x, int y)
{
    return grid.ContainsKey(y) && grid[y].ContainsKey(x) && grid[y][x] == Constants.air || !grid.ContainsKey(y) ||
            !grid[y].ContainsKey(x);
}

void setSpace(int x, int y, string value)
{
    if (!grid.ContainsKey(y))
        grid.Add(y, new Dictionary<int, string>());

    if (!grid[y].ContainsKey(x))
    {
        grid[y].Add(x, value);
    }
    else
    {
        grid[y][x] = value;
    }
        
}

class Coordinate
{
    public int x
    {
        get;
        set;
    }

    public int y
    {
        get;
        set;
    }

    public static Coordinate fromString(string input)
    {
        var parts = input.Split(",");
        
        return new Coordinate() { x = Int32.Parse(parts[0]), y = Int32.Parse(parts[1]) };
    }

    public List<Coordinate> createCoordinatesBetween(Coordinate to)
    {
        var coordinates = new List<Coordinate>();
        
        if (x < to.x) // Left to right
        {
            for (var i = x; i <= to.x; i++)
            {
                coordinates.Add(new Coordinate(){x = i, y = to.y});
            }
        } // Right to left
        else if (x > to.x)
        {
            for (var i = x; i >= to.x; i--)
            {
                coordinates.Add(new Coordinate(){x = i, y = to.y});
            }
        }
        else if (y < to.y) // Down 
        {
            for (var i = y; i <= to.y; i++)
            {
                coordinates.Add(new Coordinate(){x = to.x, y = i});
            }
        }
        else if (y > to.y) // up 
        {
            for (var i = to.y; i >= y; i--)
            {
                coordinates.Add(new Coordinate(){x = to.x, y = i});
            }
        }

        return coordinates;
    }
}

class Constants
{
    public const string stone = "🪨";
    public const string air = "☁️";
    public const string sand = "🥐";
    public const string start = "🏁";
}