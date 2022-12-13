using System.Text.Json.Nodes;

var calculation = File.ReadAllLines("input/sample.txt")
    .ToList()
    .FindAll(x => !x.Equals(""))
    .Select(x => JsonNode.Parse(x))
    .Chunk(2)
    .Select((pair, count) => Compare(pair[0], pair[1]) < 0 ? count + 1 : 0)
    .Sum();

int Compare(JsonNode left, JsonNode right) {
    if (left is JsonValue && right is JsonValue) {
        return left.GetValue<int>() - right.GetValue<int>();
    }

    var leftArray = left is JsonArray ? left.AsArray() : new JsonArray(left.GetValue<int>());
    var rightArray = right is JsonArray ? right.AsArray() : new JsonArray(right.GetValue<int>());
 
    for (var i = 0; i < Math.Min(leftArray.Count, rightArray.Count); i++)
    {
        var res = Compare(leftArray[i], rightArray[i]);
        
        if (res != 0)
        {
            return res;
        }
    }
    
    return leftArray.Count - rightArray.Count;
}

Console.WriteLine(calculation);

var partTwo = File.ReadAllLines("input/sample.txt")
    .ToList()
    .FindAll(x => !x.Equals(""))
    .Select(x => JsonNode.Parse(x)).ToList();

partTwo.Add(JsonNode.Parse("[[2]]"));
partTwo.Add(JsonNode.Parse("[[6]]"));

partTwo.Sort((left, right) => Compare(left, right));

Console.WriteLine($"Part 2: {(partTwo.IndexOf("[[2]]") + 1) * (partTwo.IndexOf("[[2]]") + 1)}");