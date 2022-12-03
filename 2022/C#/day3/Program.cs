var backpacks = File.ReadAllLines(@"./input/input.txt");

static int GetScore(Char character)
{
    return 1 + "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ".IndexOf(character);
}

var priority = 0;

// Part 1
foreach (string backpack in backpacks)
{
    var backpackItems = backpack.ToCharArray().ToList();
    var leftCompartment = backpackItems.GetRange(0, backpackItems.Count / 2);
    var rightCompartment = backpackItems.GetRange(backpackItems.Count / 2, backpackItems.Count / 2);

    priority += GetScore(leftCompartment.Intersect(rightCompartment).First());
}

Console.WriteLine("The total priority is {0}", priority);

// Part two
var backpacksList = backpacks.ToList();
var start = 0;
var badges = 0;

while (start < backpacks.Length -1)
{
    var elves = backpacksList.GetRange(start, 3);
    badges += GetScore(elves[0].Intersect(elves[1]).Intersect(elves[2]).First());
    
    start += 3;
}

Console.WriteLine("The total badge score is {0}", badges);

