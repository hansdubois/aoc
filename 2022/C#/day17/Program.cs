//
// @@@@
//
var flatPiece = new List<(int x, int y)>();
flatPiece.Add((0, 0));
flatPiece.Add((1, 0));
flatPiece.Add((2, 0));
flatPiece.Add((3, 0));

//
// .@.
// @@@
// .@.
//
var rockPiece = new List<(int x, int y)>();
rockPiece.Add((1, 0));
rockPiece.Add((0, 1));
rockPiece.Add((1, 1));
rockPiece.Add((2, 1));
rockPiece.Add((1, 2));

//
// ..@
// ..@
// @@@
//
var cornerPiece = new List<(int x, int y)>();
cornerPiece.Add((2, 2));
cornerPiece.Add((2, 1));
cornerPiece.Add((0, 0));
cornerPiece.Add((1, 0));
cornerPiece.Add((2, 0));

//
// @
// @
// @
// @
//
var lPiece = new List<(int x, int y)>();
lPiece.Add((0, 0));
lPiece.Add((0, 1));
lPiece.Add((0, 2));
lPiece.Add((0, 3));

//
// @@
// @@
//
var square = new List<(int x, int y)>();
square.Add((0, 0));
square.Add((1, 0));
square.Add((0, 1));
square.Add((1, 1));

var pieces = new List<List<(int x, int y)>>();
pieces.Add(flatPiece);
pieces.Add(rockPiece);
pieces.Add(cornerPiece);
pieces.Add(lPiece);
pieces.Add(square);

var moveLeft = (x:-1, y:0);
var moveRight = (x:1, y:0);
var moveDown = (x:0, y:1);
var height = 20;
var witdh = 7;

var itemCount = 0;
var startPadding = (x: 2, y: 3);

var finished = false;
var grid = new Grid();
Piece currentItem = null;

var input = File.ReadAllText("input/sample.txt");
var move = 0;
var stopCount = 0;

var maxY = 0;

while (stopCount < 2022)
{
    if (currentItem is null)
    {
        var newPiece = pieces[itemCount % pieces.Count];
        currentItem = new Piece(newPiece, (x: 2, y: 3 + maxY));
        currentItem.AddToGrid(grid);
        itemCount++;
    }
    else
    {
        // Do Move
        if (move >= input.Length -1)
            move = 0;
        
        // Move 
        var direction = input[move].ToString() == ">" ? (1, 0) : (-1, 0);
        
        if (grid.CanMove(currentItem, direction))
        {
            currentItem.MoveOnGrid(direction, grid);
        }
        
        move++;
        
        // Move Down
        if (grid.CanMove(currentItem, (0, -1)))
        {
           currentItem.MoveOnGrid((0, -1), grid);    
        }
        else
        {
            currentItem.Freeze(grid);
            maxY = currentItem.Parts.Max(tuple => tuple.y) + 1;
            
            currentItem = null;
            stopCount++;
        }
    }

    Draw();
    
    Thread.Sleep(500);
}

Console.WriteLine(grid.GetHighestPosition());
Console.WriteLine(maxY);

void Draw()
{
    Console.Clear();
    Console.SetCursorPosition(0,0);
    
    for (var y = height; y >= 0; y--)
    {
        Console.Write("|");
        
        for (int x = 0; x < witdh; x++)
        {
            if (y == grid.GetHighestPosition())
            {
                Console.Write(grid.GetOrDefault(x, y, "+"));
            }
            else
            {
                Console.Write(grid.GetOrDefault(x, y, "."));    
            }
        }
        
        Console.Write("|");
        
        Console.WriteLine();
    }

    for (int x = -2; x < witdh; x++)
    {
        Console.Write("_");
    }
    
    Console.WriteLine("Move {0}, Direction: {1}", move, input[move]);
}

class Piece
{
    public List<(int x, int y)> Parts { get; set; }

    public Piece(List<(int x, int y)> parts, (int x, int y) startPosition)
    {
        // Set start position
        Parts = parts.Select((part) => (part.x + startPosition.x, part.y + startPosition.y)).ToList();
    }

    public void AddToGrid(Grid grid)
    {
        foreach (var part in Parts)
        {
            grid.AddToGrid(part.x, part.y, "@");
        }
    }
    
    public void MoveOnGrid((int x, int y) movement, Grid grid)
    {
        var newPositions = new List<(int x, int y)>();

        foreach (var part in Parts)
        {
            grid.RemoveFromGrid(part.x, part.y);
            
            newPositions.Add((part.x + movement.x, part.y + movement.y));
        }

        Parts = newPositions;
        
        foreach (var part in Parts)
        {
            grid.AddToGrid(part.x, part.y, "@");
        }

    }

    public void Freeze(Grid grid)
    {
        foreach (var part in Parts)
        {
            grid.AddToGrid(part.x, part.y, "#");
        }
    }
        
}

class Grid
{
    private Dictionary<int, Dictionary<int, string>> _grid = new Dictionary<int, Dictionary<int, string>>();

    public void AddToGrid(int x, int y, string value)
    {
        if (!_grid.ContainsKey(y))
            _grid.Add(y, new Dictionary<int, string>());

        if (!_grid[y].ContainsKey(x))
        {
            _grid[y].Add(x, value);
        }
        else
        {
            _grid[y][x] = value;
        }
    }

    public string GetOrDefault(int x, int y, string defaultValue)
    {
        if (_grid.ContainsKey(y) && _grid[y].ContainsKey(x))
        {
            return _grid[y][x];
        }

        return defaultValue;
    }

    public void RemoveFromGrid(int x, int y)
    {
        if (_grid.ContainsKey(y) && _grid[y].ContainsKey(x)) {
            _grid[y].Remove(x); }
    }

    private bool HasValueFor(int x, int y)
    {
        return _grid.ContainsKey(y) && _grid[y].ContainsKey(x) && _grid[y][x] == "#";
    }

    public bool CanMove(Piece piece, (int x, int y) movement)
    {
        foreach (var part in piece.Parts)
        {
            if (part.x + movement.x < 0 || part.x + movement.x > 6)
                return false;
            
            if (part.y + movement.y < 0)
                return false;
            
            if (HasValueFor(part.x + movement.x, part.y + movement.y))
                return false;
        }
        
        return true;
    }

    public int GetHighestPosition()
    {
        var y = Int32.MinValue;
        
        var rowsWithItems = _grid.ToList().FindAll((item) => item.Value.Count > 0);

        if (rowsWithItems.Count > 0)
        {
            foreach (var row in rowsWithItems)
            {
                if (row.Key > y)
                    y = row.Key + 1;
            }

            return y;
        }

        return 0;
    }
}