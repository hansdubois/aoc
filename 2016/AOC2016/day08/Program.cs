using System.ComponentModel;
using System.Text.RegularExpressions;

var width = 50;
var height = 6;

var grid = new Dictionary<int, Dictionary<int, string>>();

for (var y = 0; y < height; y++)
{
    var dict = new Dictionary<int, string>();

    for (var x = 0; x < width; x++)
    {
        dict.Add(x, ".");
    }
    
    grid.Add(y, dict);
}

printGrid(grid);
Console.WriteLine();

var input = File.ReadLines("input/input.txt");

foreach (var instruction in input)
{
    if (instruction.Substring(0, 4) == "rect")
    {
        var regex = new Regex(@"rect (?<width>\d+)x(?<height>\d+)");
        var matches = regex.Match(instruction);
        
        drawRect(grid, Int32.Parse(matches.Groups["width"].ToString()), Int32.Parse(matches.Groups["height"].ToString()));
    } 
    else if (instruction.Substring(0, 13) == "rotate column")
    {
        var regex = new Regex(@"rotate column x\=(?<column>\d+) by (?<amount>\d+)");
        var matches = regex.Match(instruction);
        
        rotateColumn(grid, Int32.Parse(matches.Groups["column"].ToString()), Int32.Parse(matches.Groups["amount"].ToString()) );
    }
    else if (instruction.Substring(0, 10) == "rotate row")
    {
        var regex = new Regex(@"rotate row y\=(?<row>\d+) by (?<amount>\d+)");
        var matches = regex.Match(instruction);
        
        rotateRow(grid, Int32.Parse(matches.Groups["row"].ToString()), Int32.Parse(matches.Groups["amount"].ToString()) );
    }
    
    // printGrid(grid);
    // Console.WriteLine();
}

printGrid(grid);

static void printGrid(Dictionary<int, Dictionary<int, string>> grid)
{
    var count = 0;
    
    for (var y = 0; y < grid.Count; y++)
    {
        for (var x = 0; x < grid[y].Count; x++)
        {
            if (grid[y][x] == "#")
            {
                count++;
                Console.Write("🔥");
            }
            else
            {
                Console.Write("🌲");
            }
        }

        Console.WriteLine();
    }
    
    Console.WriteLine(count);
}

static void drawRect(Dictionary<int, Dictionary<int, string>> grid, int width, int height)
{
    for (var y = 0; y < height; y++)
    {
        for (var x = 0; x < width; x++)
        {
            grid[y][x] = "#";
        }
    }
}

static void rotateColumn(Dictionary<int, Dictionary<int, string>> grid, int column, int amount)
{
    var instructions = new List<Instruction>();

    for (var y = 0; y < grid.Count; y++)
    {
        if (grid[y][column] == "#")
        {
            // Do not reset if we set
            if (instructions.FindAll(x => (x.Y == y && x.Value == "#")).Count == 0)
            {
                instructions.Add(new Instruction(column, y, "."));    
            }


            var move = y + amount;

            if (move >= grid.Count)
            {
                move = move - grid.Count;
            }
            
            instructions.Add(new Instruction(column, move, "#"));
        }
    }
    
    applyInstructions(grid, instructions);
}

static void rotateRow(Dictionary<int, Dictionary<int, string>> grid, int row, int amount)
{
    var instructions = new List<Instruction>();

    for (var x = 0; x < grid[row].Count; x++)
    {
        if (grid[row][x] == "#")
        {
            // Do not reset if we set
            if (instructions.FindAll(inst => (inst.X == x && inst.Value == "#")).Count == 0)
            {
                instructions.Add(new Instruction(x, row, "."));    
            }
            
            var move = x + amount;

            if (move >= grid[row].Count)
            {
                move = move - grid[row].Count;
            }
            
            instructions.Add(new Instruction(move, row, "#"));
            
        }
    }
    
    applyInstructions(grid, instructions);
}

static void applyInstructions(Dictionary<int, Dictionary<int, string>> grid, List<Instruction> instructions)
{
    foreach (var instruction in instructions)
    {
        grid[instruction.Y][instruction.X] = instruction.Value;
    }
}

struct Instruction
{
    public int X { get; }
    public int Y { get; }
    public string Value { get; }


    public Instruction(int x, int y, string value)
    {
        X = x;
        Y = y;
        Value = value;
    } 
}

