var mode = "sample";
var instructions = File.ReadAllLines("input/" + mode + ".txt");

var values = new List<int>();
var pastValues = new List<int>();

foreach (var instruction in instructions)
{
    var inputList = instruction.Split( ).Select(Int32.Parse).ToList();
    var sequences = getSequences(inputList);
    
    values.Add(sequences.Select(x => x.Last()).Sum());
    pastValues.Add(firstValue(sequences));
}

Console.WriteLine();
Console.WriteLine("Part One: " + values.Sum());
Console.WriteLine("Part Two: " + pastValues.Sum());

List<List<int>> getSequences(List<int> inputList)
{
    var sequences = new List<List<int>>();
    sequences.Add(inputList);

    var nextLine = inputList;

    while (nextLine.Count(x => x != 0) > 0)
    {
        var sequence = new List<int>();

        for (var i = 0; i < nextLine.Count - 1; i++)
        {
            var difference = nextLine[i + 1] - nextLine[i];
            sequence.Add(difference);
        }

        nextLine = sequence;

        sequences.Add(sequence);
    }

    return sequences;
}


int firstValue(List<List<int>>  sequences)
{
    var prependValues = new List<int>();
    
    for (var i = sequences.Count - 1; i >= 0; i--)
    {
        if (i == sequences.Count - 1)
        {
            prependValues.Add(0);    
        }
        else
        {
            var previousValue = prependValues.Last();
            prependValues.Add(sequences[i][0] - previousValue);
        }
    }
    
    return prependValues.Last();
}