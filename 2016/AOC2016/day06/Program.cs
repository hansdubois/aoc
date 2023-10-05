var input = File.ReadLines("input/input.txt");
var charmap = new Dictionary<int, Dictionary<string, int>>()
{
    { 0, new Dictionary<string, int>() },
    { 1, new Dictionary<string, int>() },
    { 2, new Dictionary<string, int>() },
    { 3, new Dictionary<string, int>() },
    { 4, new Dictionary<string, int>() },
    { 5, new Dictionary<string, int>() },
    { 6, new Dictionary<string, int>() },
    { 7, new Dictionary<string, int>() }
};

foreach (var line in input)
{
    var index = 0;
    
    foreach (var character in line)
    {
        var stringCharacter = character.ToString();

        if (!charmap[index].ContainsKey(stringCharacter))
        {
            charmap[index].Add(stringCharacter, 1);    
        }
        else
        {
            charmap[index][stringCharacter]++;
        }
        
        index++;
    }
}

foreach (var map in charmap)
{
    Console.Write(map.Value.OrderByDescending(x => x.Value).Take(1).First().Key);
}

Console.WriteLine();

foreach (var map in charmap)
{
    Console.Write(map.Value.OrderBy(x => x.Value).Take(1).First().Key);
}

