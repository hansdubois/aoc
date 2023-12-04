// See https://aka.ms/new-console-template for more information

using System.Text.RegularExpressions;

var input = File.ReadAllLines("input/sample.txt");

var possibleGames = new List<long>();

foreach (var gameString in input)
{
    var reds = 0;
    var blues = 0;
    var greens = 0;
    
    var gameParts = gameString.Split(":");
    var gameNumber = Int64.Parse(gameParts[0].Split(" ")[1].ToString().Replace(":", ""));

    var cubeSets = gameParts[1].Split(";");

    var possible = true;

    foreach (var cubeSet in cubeSets)
    {
        var red = new Regex( @"(\d+) red");
        var redMatch = red.Matches(cubeSet);

        if (redMatch.Count > 0 && Int64.Parse(redMatch[0].ToString().Split(" ")[0]) > 12)
        {
            possible = false;
            break;
        }
        
        var blue = new Regex( @"\d+ blue");
        var blueMatch = blue.Matches(cubeSet);
        if (blueMatch.Count > 0 && Int64.Parse(blueMatch[0].ToString().Split(" ")[0]) > 14)
        {
            possible = false;
            break;
        }
        
        
        var green = new Regex( @"\d+ green");
        var greenMatch = green.Matches(cubeSet);
        if (greenMatch.Count > 0 && Int64.Parse(greenMatch[0].ToString().Split(" ")[0]) > 13)
        {
            possible = false;
            break;
        }
    }

    if (possible)
    {
        possibleGames.Add(gameNumber);
    }
    
}


var total = new List<int>();


foreach (var gameString in input)
{
    var reds = 0;
    var blues = 0;
    var greens = 0;
    
    var gameParts = gameString.Split(":");
    var gameNumber = Int64.Parse(gameParts[0].Split(" ")[1].ToString().Replace(":", ""));

    var cubeSets = gameParts[1].Split(";");

    foreach (var cubeSet in cubeSets)
    {
        var red = new Regex( @"(\d+) red");
        var redMatch = red.Matches(cubeSet);

        if (redMatch.Count > 0)
        {
            var redNumber = Int32.Parse(redMatch[0].ToString().Split(" ")[0]);
            if (redNumber > reds)
            {
                reds = redNumber;
            }
        }
        
        var blue = new Regex( @"\d+ blue");
        var blueMatch = blue.Matches(cubeSet);
        if (blueMatch.Count > 0)
        {
            var blueNumber = Int32.Parse(blueMatch[0].ToString().Split(" ")[0]);
            if (blueNumber > blues)
            {
                blues = blueNumber;
            }
        }
        
        var green = new Regex( @"\d+ green");
        var greenMatch = green.Matches(cubeSet);
        if (greenMatch.Count > 0)
        {
            var greenNumber = Int32.Parse(greenMatch[0].ToString().Split(" ")[0]);
            if (greenNumber > greens)
            {
                greens = greenNumber;
            }
        }
    }
    
    total.Add(reds * blues * greens);

    Console.WriteLine($"Game: {gameNumber} {reds * blues * greens}");
}

Console.WriteLine(total.Sum());

