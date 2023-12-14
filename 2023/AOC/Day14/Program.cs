var lines = File.ReadAllLines("input/sample.txt");
Calculate(lines);

void PrintGrid(Rock[,] rocks)
{
    int height = rocks.GetLength(0);
    int width = rocks.GetLength(1);

    Console.WriteLine();
    for(int y = 0; y < height; y++)
    {
        for(int x = 0; x < width; x++)
        {
            Console.Write(rocks[y,x]);
        }
        
        Console.WriteLine();    
    }
}

string HashGrid(Rock[,] arr)
{
    int height = arr.GetLength(0);
    int width = arr.GetLength(1);

    var ha = "";
    
    for(int y = 0; y < height; y++)
    {
        for(int x = 0; x < width; x++)
        {
            ha += arr[y, x].Type.ToString();
        }
    }

    return ha;
}

void Calculate(string[] lines)
{
    int height = lines.Length;
    int width = lines[0].Length;
    
    Rock[,] grid = new Rock[height, width];
    
    for(int y = 0; y < height; y++)
    {
        for(int x = 0; x < width; x++)
        {
            grid[y, x] = new Rock(y, x, lines[y][x].ToString());
        }
    }
    
    Dictionary<string, Rock[,]> cache = new Dictionary<string, Rock[,]>();

    PrintGrid(grid);

    string gridHash = "";
    long runIdWhenFound = 0;
    
    var runTimes = 1000000000;
    for (int i = 0; i < runTimes; i++)
    {
        string hash = HashGrid(grid);
        
        if (cache.ContainsKey(hash))
        {
            // We have been here before
            runIdWhenFound = i;
            gridHash = hash;
            
            break;
        }
        
        // North
        for (int y = 0; y < height; y++)
        {
            for (int x = 0; x < width; x++)
            {
                Rock rock = grid[y, x];

                if (rock.Type == Types.Rolling)
                {
                    int yPrevious = y - 1;

                    while (yPrevious >= 0 && grid[yPrevious, x].Type == Types.Sand)
                    {
                        var save = grid[yPrevious, x];
                        grid[yPrevious, x] = rock;
                        grid[yPrevious + 1, x] = save;
                        yPrevious--;
                    }
                }
            }
        }

        // West
        for (int x = 0; x < width; x++)
        {
            for (int y = 0; y < height; y++)
            {
                Rock rock = grid[y, x];

                if (rock.Type == Types.Rolling)
                {
                    int xPrevious = x - 1;

                    while (xPrevious >= 0 && grid[y, xPrevious].Type == Types.Sand)
                    {
                        var save = grid[y, xPrevious];
                        grid[y, xPrevious] = rock;
                        grid[y, xPrevious + 1] = save;
                        xPrevious--;
                    }
                }
            }
        }

        // South
        for (int y = height - 1; y >= 0; y--)
        {
            for (int x = 0; x < width; x++)
            {
                Rock rock = grid[y, x];

                if (rock.Type == Types.Rolling)
                {
                    int yPrevious = y + 1;

                    while (yPrevious < grid.GetLength(0) && grid[yPrevious, x].Type == Types.Sand)
                    {
                        var save = grid[yPrevious, x];
                        grid[yPrevious, x] = rock;
                        grid[yPrevious - 1, x] = save;
                        yPrevious++;
                    }
                }
            }
        }

        // East
        for (int x = width - 1; x >= 0; x--)
        {
            for (int y = 0; y < height; y++)
            {
                Rock rock = grid[y, x];

                if (rock.Type == Types.Rolling)
                {
                    int xNext = x + 1;

                    while (xNext < grid.GetLength(1) && grid[y, xNext].Type == Types.Sand)
                    {
                        var save = grid[y, xNext];
                        grid[y, xNext] = rock;
                        grid[y, xNext - 1] = save;
                        xNext++;
                    }
                }
            }
        }

        cache[hash] = (Rock[,])grid.Clone();
    }

    List<string> loopHashes = new List<string>();

    while(!loopHashes.Contains(gridHash))
    {
        loopHashes.Add(gridHash);
        
        gridHash = HashGrid(cache[gridHash]);
    }
    
    Console.WriteLine("RAN" + runIdWhenFound);

    long TimesToGo = runTimes - runIdWhenFound - 1;
    long indexWithExtraRuns = TimesToGo % loopHashes.Count;
    
    string final = loopHashes[(int)indexWithExtraRuns];

    var finalRocks = cache[final];

    PrintGrid(finalRocks);

    int sum = 0;
    for (int y = 0; y < height; y++)
    {
        for (int x = 0; x < width; x++)
        {
            Rock r = finalRocks[y, x];
            
            if(r.Type == Types.Rolling)
            {
                int count = height - y;

                sum += count;
            }
        }
    }
   
    Console.WriteLine(sum);
}

public enum Types
{
    Sand = '.',
    Square = '#',
    Rolling = 'O'
};

public class Rock
{
    public Types Type { get; }

    public int X { get; }
    public int Y { get; }


    public Rock(int y, int x, string value)
    {
        X = x;
        Y = y;

        switch (value)
        {
            case ".":
                Type = Types.Sand;
                break;

            case "#":
                Type = Types.Square;
                break;

            default:
                Type = Types.Rolling;
                break;
        }
    }
    public override string ToString()
    {
        return $"{(char)Type}";
    }
}