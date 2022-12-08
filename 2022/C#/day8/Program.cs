using System.Drawing;

var trees = File.ReadAllLines(@"./input/sample.txt");

var width = trees[0].ToCharArray().Length;
var height = trees.Length;

var forrest = new Dictionary<int, List<int>>();

for (var i =0; i < trees.Length; i++)
{
    forrest.Add(i, trees[i].ToCharArray().Select((x) => x - '0').ToList());
}

var visible = new List<string>();

// Left to right
for (var y = 0; y < forrest.Count; y++)
{
    var heighestTree = 0;
    
    for (var x = 0; x < forrest[y].Count; x++)
    {
        if (forrest[y][x] > heighestTree || heighestTree == 0 && forrest[y][x] == 0 && x == 0 )
        {
            heighestTree = forrest[y][x];
            visible.Add($"{y}-{x}");
        }
    }
}

// Right to left
for (var y = 0; y < forrest.Count; y++)
{
    var heighestTree = 0;

    for (var x = (forrest[y].Count -1); x >= 0 ; x--)
    {
        if (forrest[y][x] > heighestTree || heighestTree == 0 && forrest[y][x] == 0 && x == (forrest[y].Count -1))
        {
            heighestTree = forrest[y][x];
            visible.Add($"{y}-{x}");
        }
    }
}

// Top to bottom
for (var x = 0; x < width; x++)
{
    var heighestTree = 0;

    for (var y = 0; y < height; y++)
    {
        if (forrest[y][x] > heighestTree || heighestTree == 0 && forrest[y][x] == 0 && y == 0)
        {
            heighestTree = forrest[y][x];
            visible.Add($"{y}-{x}");
        }
    }
}

// bottom to top
for (var x = 0; x < width; x++)
{
    var heighestTree = 0;

    for (var y = (height - 1); y >= 0; y--)
    {
        if (forrest[y][x] > heighestTree || heighestTree == 0 && forrest[y][x] == 0 && y == (height -1))
        {
            heighestTree = forrest[y][x];
            visible.Add($"{y}-{x}");
        }
    }
}

var dest = visible.Distinct();

var highestScenic = 0;

for (var y = 0; y < forrest.Count; y++)
{
    for (var x = 0; x < height; x++)
    {
        if (x == 0 || y == 0 || x == width - 1 || y == height - 1)
        {
            continue;
        }

        var topCount = y;
        var bottomCount = y;
        var leftCount = x;
        var rightCount = x;

        var found = false;
        while (!found)
        {
            if (rightCount == 0 || forrest[y][rightCount] > forrest[y][x])
            {
                found = true;
                rightCount = x - rightCount;
            }
            else
            {
                rightCount--;
            }    
        }
        
        found = false;
        while (!found)
        {
            if (leftCount == height -1 || forrest[y][leftCount] > forrest[y][x])
            {
                found = true;
                leftCount = leftCount - x;
            }
            else
            {
                leftCount++;
            }    
        }
        
        found = false;
        while (!found)
        {
            if (topCount == 0 || forrest[topCount][x] > forrest[y][x])
            {
                found = true;
                topCount = y - topCount;
            }
            else
            {
                topCount--;
            }    
        }
        
        found = false;
        while (!found)
        {
            if (bottomCount == 99 || forrest[bottomCount][x] > forrest[y][x])
            {
                found = true;
                bottomCount = bottomCount - y;
            }
            else
            {
                bottomCount++;
            }    
        }

        var score = bottomCount * topCount * leftCount * rightCount;

        if (score > highestScenic)
        {
            highestScenic = score;
        }
    }
}



for (var y = 0; y < forrest.Count; y++)
{
    for (var x = 0; x < height; x++)
    {
        if (dest.Contains($"{y}-{x}"))
        {
            Console.BackgroundColor = ConsoleColor.Green;
            Console.ForegroundColor = ConsoleColor.DarkGreen;
            Console.Write("🎄");
            Console.BackgroundColor = ConsoleColor.Black;
            Console.ForegroundColor = ConsoleColor.Black;
        }
        else
        {
            Console.Write("🌲");
        }
    }
    Console.Write("\n");
}

Console.WriteLine(highestScenic);