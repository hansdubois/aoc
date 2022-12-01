var elves = File.ReadAllText(@"./input/calories.txt")
    .Split("\n\n")
    .Select(x => x.Split("\n").Aggregate(0, (acc, c) => acc + Int32.Parse(c))).OrderByDescending(value=>value).ToList();

Console.WriteLine("Max: {0}, Three: {1}", elves.GetRange(0, 1).Sum(), elves.GetRange(0, 3).Sum());