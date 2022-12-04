using System.IO.MemoryMappedFiles;

var pairs = File.ReadAllLines(@"./input/input.txt");

var counter = 0;
var overlap = 0;

foreach (var pair in pairs)
{
    var elves = pair.Split(',').Select<string, Elf>(assignment => new Elf(assignment)).ToList();

    if ((elves[0].from >= elves[1].from && elves[0].to <= elves[1].to) || 
        (elves[1].from >= elves[0].from && elves[1].to <= elves[0].to))
        counter++;
    
    if (elves[0].sections.Intersect(elves[1].sections).ToList().Count > 0)
        overlap++;
}

Console.WriteLine("Amount of complete intersections {0}", counter);
Console.WriteLine("Amount of total intersections {0}", overlap);

class Elf
{
    public readonly int from;
    public readonly int to;
    public readonly List<string> sections = new List<string>();

    public Elf(string assignment)
    {
        var split = assignment.Split('-');

        from = Int32.Parse(split[0]);
        to = Int32.Parse(split[1]);
        
        for (int i = from; i <= to; i++)
        {
            sections.Add(i.ToString());
        }
    }
}