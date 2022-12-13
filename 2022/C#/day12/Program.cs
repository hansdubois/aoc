var lines = File.ReadAllLines("input/sample.txt");
var Characters = "abcdefghijklmnopqrstuvwxyz";

var grid = new List<List<string>>();
var Squares = new Dictionary<int, Dictionary<int, Square>>();

Square start = null;
Square end = null;

var possibleStarts = new List<Square>();

foreach (var line in lines)
{   
    
    grid.Add(line.ToCharArray().Select(x => x.ToString()).ToList());
}

// Create square objects
for (var y = 0; y < lines.Length; y++)
{
    Squares[y] = new Dictionary<int, Square>();
    var characters = lines[y].ToCharArray().Select(x => x.ToString()).ToList();
    
    for (var x = 0; x < characters.Count; x++)
    {
        var gridValue = characters[x];
        var height = 0;

        bool isStart = gridValue == "S";
        bool isEnd = gridValue == "E";

        if (gridValue == "S")
        {
            height = 0;
        } else if (gridValue == "E")
        {
            height = 25;
        }
        else
        {
            height = Characters.IndexOf(gridValue);
        }
        
        Squares[y][x] = new Square(new Coordinate() {x = x, y = y}, height, isStart, isEnd);

        if (isStart)
        {
            start = Squares[y][x];
        }

        if (isEnd)
        {
            end = Squares[y][x];
        }
        
        if (gridValue == "a" && (x == 0 || y == 0 || y == grid.Count || x == grid[0].Count))
        {
            possibleStarts.Add(Squares[y][x]);
        }
    }
}

// Loop over again and add neighbours
for (var y = 0; y < Squares.Count; y++)
{
    for (var x = 0; x < Squares[y].Count; x++)
    {
        var current = Squares[y][x];
        
        // Left
        if (x != 0 && canTravelToSquare(current, Squares[y][x-1]))
        {
            current.AddNeighbour(Squares[y][x-1]);
        }

        // right
        if (x < (Squares[y].Count - 1) && canTravelToSquare(current, Squares[y][x + 1]))
        {
            current.AddNeighbour(Squares[y][x + 1]);
        }
        
        // top 
        if (y != 0 && canTravelToSquare(current, Squares[y - 1][x]))
        {
            current.AddNeighbour(Squares[y - 1][x]);
        }

        // bottom
        if (y < Squares.Count - 1 && canTravelToSquare(current, Squares[y+1][x]))
        {
            current.AddNeighbour(Squares[y+1][x]);
        }
    }
}

bool canTravelToSquare(Square from, Square to)
{
    return (from.height + 1 == to.height || from.height >= to.height);
}

Console.WriteLine("Fastest way to end takes {0} steps", Pathfinder.FindPath(start, end).Count);

var startsResult = new List<int>();

foreach (var possibleStart in possibleStarts)
{
    startsResult.Add(Pathfinder.FindPath(possibleStart, end).Count);
}

Console.WriteLine("Fastest alternative way to end takes {0} steps", startsResult.FindAll(x => x > 0).MinBy(x => x));

class Pathfinder
{
    static public List<Square> FindPath(Square start, Square end)
    {
        var hugeList = new Queue<Square>();
        hugeList.Enqueue(start);

        var cameFrom = new Dictionary<Square, Square?>
        {
            [start] = null
        };

        while (hugeList.Count > 0)
        {
            var current = hugeList.Dequeue();

            if (current.coordinate.toString() == end.coordinate.toString())
            {
                break;
            }
            
            foreach (var next in current.neighbours)
            {
                if (!cameFrom.ContainsKey(next))
                {
                    hugeList.Enqueue(next);
                    cameFrom[next] = current;
                }
            }
        }

        return BuildPath(start, end, cameFrom);
    }
    
    private static List<Square> BuildPath(Square start, Square end, IReadOnlyDictionary<Square, Square?> cameFrom)
    {
        // End was not found, so no path can be built.
        if (!cameFrom.ContainsKey(end))
        {
            return new List<Square>();
        }
        
        var pathSquare = end;
        var path = new List<Square>();
        
        while (pathSquare != start)
        {
            path.Add(pathSquare);
            pathSquare = cameFrom[pathSquare]!; 
        }
        path.Reverse();

        return path;
    }
}

class Square
{
    public readonly Coordinate coordinate;
    public readonly int height;
    public readonly bool isStart;
    public readonly bool isEnd;
    public List<Square> neighbours = new List<Square>();

    public Square(Coordinate coordinate, int height, bool isStart, bool isEnd)
    {
        this.coordinate = coordinate;
        this.height = height;
        this.isStart = isStart;
        this.isEnd = isEnd;
    }

    public void AddNeighbour(Square neighbour)
    {
        neighbours.Add(neighbour);
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

    public string toString()
    {
        return $"{x}-{y}";
    }
}