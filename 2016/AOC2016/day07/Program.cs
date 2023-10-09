using System.Text.RegularExpressions;

var input = File.ReadLines("input/input.txt");
var TLS = 0;
var SSLS = new List<string>();

foreach (var line in input)
{
    var parts = line.Split('[', ']');
    var ips = new List<string>();
    var hyperNets = new List<string>();
    
    for(var i = 0; i < parts.Length; i++)
    {
        if (i % 2 == 0)
        {
            ips.Add(parts[i]);
        }
        else
        {
            hyperNets.Add(parts[i]);
        }
    }

    var hyperNetsWithAbba = hyperNets.FindAll(hasAbba);

    if (hyperNetsWithAbba.Count == 0)
    {
        if (ips.FindAll(hasAbba).Count > 0)
        {
            TLS++;
        }
    }

    

    foreach (var ip in ips)
    {
        foreach (var aba in getAba(ip))
        {
            foreach (var net in hyperNets)
            {
                if (net.Contains(aba.Value))
                {
                    SSLS.Add(line);
                    
                    continue;
                }
            }
        }
    }
    
}

Console.WriteLine(TLS);
Console.WriteLine(SSLS.Select(x => x).Distinct().Count());

static bool hasAbba(string input)
{
    for (var i = 0; i <= input.Length - 4; i++)
    {
        var stringToCheck = input.Substring(i, 4);
        if (stringToCheck[0] == stringToCheck[3] && stringToCheck[1] == stringToCheck[2] && stringToCheck[0] != stringToCheck[1])
        {
            return true;
        }
    }

    return false;
}

static Dictionary<string, string> getAba(string input)
{
    var aba = new Dictionary<string, string>();

    for (var i = 0; i <= input.Length - 3; i++)
    {
        var stringToCheck = input.Substring(i, 3);
        if (stringToCheck[0] == stringToCheck[2] && stringToCheck[1] != stringToCheck[2] && stringToCheck[1] != stringToCheck[0])
        {
            if (!aba.ContainsKey(stringToCheck))
            {
                aba.Add(stringToCheck, $"{stringToCheck[1]}{stringToCheck[0]}{stringToCheck[1]}");
            }
        }
    }

    return aba;
}