using System.Text.RegularExpressions;

var mode = "sample";

var lines = File.ReadAllLines("input/" + mode + ".txt");
var totalCount = 0;

var watch = System.Diagnostics.Stopwatch.StartNew();
// the code that you want to measure comes here


foreach (var line in lines)
{
    var splitOnSpace = line.Split(" ");

    var arrangement = splitOnSpace[0];
    var groups = splitOnSpace[1];

    var arrangementPartTwo = arrangement;
    var groupsPartTwo = groups;
    
    for (int i = 0; i < 4; i++)
    {
        arrangementPartTwo += "?" + arrangement;
        groupsPartTwo += "," + groups;
    }

    var count = findAllArrangementsFor(arrangementPartTwo, groupsPartTwo);
    totalCount += count;
    
    Console.WriteLine(arrangement + count);
     Console.WriteLine();
}
watch.Stop();
var elapsedMs = watch.ElapsedMilliseconds;
Console.WriteLine(totalCount);
Console.WriteLine(elapsedMs);

int findAllArrangementsFor(string arrangement, string groups)
{
    var charList = arrangement.ToCharArray().Select(x => x.ToString()).ToList();
    
    var possibilities = new List<string>();

    for (var i = 0; i < charList.Count; i++)
    {   
        var charsToAdd = new List<string>();

        if (charList[i] == "?")
        {
            charsToAdd.Add(".");
            charsToAdd.Add("#");
        }
        else
        {
            charsToAdd.Add(charList[i]);
        }

        if (i == 0)
        {
            possibilities = charsToAdd;
        }
        else
        {
            possibilities = addCharactersToAll(possibilities, charsToAdd, groups, charList.Count);
        }
    }

    var regex = new Regex(@"(\#+)");
    var groupsSplitted = groups.Split(",").Select(Int32.Parse).ToList();

    var matchCount = 0;
    
    foreach (var possibility in possibilities)
    {
        var matches = regex.Matches(possibility);
        
        if (matches.Count == groupsSplitted.Count)
        {
            //Console.WriteLine("Possible Match for( "+ matches.Count + "): " + groups + " Found : " + possibility);

            var match = true;

            for (var i = 0; i < matches.Count; i++)
            {
                if (matches[i].Length != groupsSplitted[i])
                {
                    match = false;
                    
                    break;
                }
            }

            if (match)
            {
                matchCount++;
                //Console.WriteLine("!!! Match for( "+ matches.Count + "): " + groups + " Found : " + possibility);
            }
            
        }
    }

    return matchCount;
}

List<string> addCharactersToAll(List<string> input, List<string> charsToAdd, string groups, int maxLength)
{
    var newList = new List<string>();
        
    var regex = new Regex(@"(\#+)");
    var groupsSplitted = groups.Split(",").Select(Int32.Parse).ToList();

    foreach (var line in input)
    {
        foreach (var charToAdd in charsToAdd)
        {
            if (charToAdd == ".")
            {
                var addition = line + charToAdd;
                var matches = regex.Matches(addition);

                // We have a possibilty
                if (matches.Count <= groupsSplitted.Count)
                {   
                    var FoundGroups = groupsSplitted.Take(matches.Count).ToList();
                    var leftToAdd = groupsSplitted.Skip(matches.Count).Take(groupsSplitted.Count - matches.Count).ToList().Sum();

                    
                    var match = true;
                
                    for (int i = 0; i < matches.Count; i++)
                    {
                        if (matches[i].Length != FoundGroups[i])
                        {
                            match = false;
                        }
                    }
                    
                    if (addition.Length + leftToAdd > maxLength)
                    {
                        match = false;
                    }
                
                
                    if (match || matches.Count == 0)
                    {
                        newList.Add(addition);    
                    }
                }    
            } 
            else if (charToAdd == "#")
            {
                var addition = line + charToAdd;
                
                var matches = regex.Matches(addition);
                var FoundGroups = groupsSplitted.Take(matches.Count).ToList();

                if (matches.Count > FoundGroups.Count())
                {
                    continue;
                }
                
                var match = true;
                
                for (int i = 0; i < matches.Count; i++)
                {
                    if (matches[i].Length > FoundGroups[i])
                    {
                        match = false;
                    }
                }
                
                var leftToAdd = groupsSplitted.Skip(matches.Count).Take(groupsSplitted.Count - matches.Count).ToList().Sum();
                if (addition.Length + leftToAdd > maxLength)
                {
                    match = false;
                }

                if (match)
                {
                    newList.Add(addition);    
                }
                
            }
            else
            {
                newList.Add(line + charToAdd);   
            }
        }
    }

    return newList;
}