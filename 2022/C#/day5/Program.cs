using System.Text.RegularExpressions;

// var Storage = new Dictionary<int, List<string>>()
// {
//     {1, new List<string>(){"Z", "N"}},
//     {2, new List<string>(){"M", "C", "D"}},
//     {3, new List<string>(){"P"}},
// };

var Storage = new Dictionary<int, List<string>>()
{
    {1, new List<string>(){"M", "J", "C", "B", "F", "R", "L", "H"}},
    {2, new List<string>(){"Z", "C", "D"}},
    {3, new List<string>(){"H", "J", "F", "C", "N", "G", "W"}},
    {4, new List<string>(){"P", "J", "D", "M", "T", "S", "B"}},
    {5, new List<string>(){"N", "C", "D", "R", "J"}},
    {6, new List<string>(){"W", "L", "D", "Q", "P", "J", "G", "Z"}},
    {7, new List<string>(){"P", "Z", "T", "F", "R", "H"}},
    {8, new List<string>(){"L", "V", "M", "G"}},
    {9, new List<string>(){"C", "B", "G", "P", "F", "Q", "R", "J"}}
};

var instructions = File.ReadAllLines(@"./input/input.txt").Select(Instruction =>
{
    Regex rx = new Regex(@"^move ([0-9]+) from ([0-9]+) to ([0-9]+)$");
    var match = rx.Match(Instruction);
    var groups = match.Groups;

    return new Instruction(Int32.Parse(groups[1].Value), Int32.Parse(groups[2].Value), Int32.Parse(groups[3].Value));
});

// foreach (var instruction in instructions)
// {
//     var repeat = instruction.Times;
//
//     while (repeat > 0)
//     {
//         var taken = Storage[instruction.From].Last();
//         Storage[instruction.From].RemoveAt(Storage[instruction.From].Count -1);
//         
//         Storage[instruction.To].Add(taken);
//         
//         repeat--;
//     }
// }

foreach (var instruction in instructions)
{
    var taken = Storage[instruction.From].GetRange(Storage[instruction.From].Count - instruction.Times, instruction.Times);
    Storage[instruction.From].RemoveRange(Storage[instruction.From].Count - instruction.Times, instruction.Times);
    Storage[instruction.To].AddRange(taken);
    

    // while (repeat > 0)
    // {
    //     var taken = Storage[instruction.From].Last();
    //     Storage[instruction.From].RemoveAt(Storage[instruction.From].Count -1);
    //     Storage[instruction.To].Add(taken);
    //     repeat--;
    // }
}

foreach (var location in Storage)
{
    Console.Write(location.Value.Last());    
}

class Instruction
{
    public readonly int Times;
    public readonly int From;
    public readonly int To;

    public Instruction(int times, int from, int to)
    {
        Times = times;
        From = from;
        To = to;
    }
}