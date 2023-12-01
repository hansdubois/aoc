using System.Text.RegularExpressions;


var calibrationValues = File.ReadAllLines("input/input.txt");

Regex regex = new Regex( @"\d");
var part1 = 
    File.ReadAllLines("input/input.txt")
    .Select(x => regex.Matches(x))
    .Select(matches => Int64.Parse($"{matches.First()}{matches.Last()}")).Sum();

Console.WriteLine($"Part 1: {part1}");

// Can't replace with the number for part two, to cater for eightwo2dfadf234
var replacements = new Dictionary<string, string>()
{
    { "one", "o1e" },
    { "two", "t2o" },
    { "three", "t3e" },
    { "four", "f4r" },
    { "five", "f5e" },
    { "six", "s6x" },
    { "seven", "s7n" },
    { "eight", "e8t" },
    { "nine", "n9e" },
    { "zero", "z0o" },
};

var partTwo = new long();

foreach (var calibrationValue in calibrationValues)
{
    var correctedCalibrationValue = calibrationValue;
    foreach (var replacement in replacements)
    {
        correctedCalibrationValue = correctedCalibrationValue.Replace(replacement.Key, replacement.Value);
    }
    
    var matches = regex.Matches(correctedCalibrationValue);
    var total = Int64.Parse($"{matches.First()}{matches.Last()}");
    
    partTwo += total;
}

Console.WriteLine(partTwo);
