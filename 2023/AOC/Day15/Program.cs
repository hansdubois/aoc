// See https://aka.ms/new-console-template for more information

using System.Text;
var inputString = "rn=1,cm-,qp=3,cm=2,qp-,pc=4,ot=9,ab=5,pc-,pc=6,ot=7";
var input = inputString.Split(",");

var count = 0;

foreach (var a in input)
{
    count += hash(a);
}

int hash(string input)
{
    var bytes = Encoding.ASCII.GetBytes(input).Select(x => Int32.Parse(x.ToString()));
    var value = 0;
    
    foreach (var b in bytes)
    {
        value += b;
        value *= 17;
        value %= 256;
    }

    return value;
}

var boxes = new Dictionary<int, Dictionary<string, Lens>>();

// Part 2
foreach (var instruction in input)
{
    if (instruction.Contains("="))
    {
        var lensInput = instruction.Split("=");
        var label = lensInput[0];
        var focalPoint = Int32.Parse(lensInput[1]);
        var boxNr = hash(label);

        if (!boxes.ContainsKey(boxNr))
        {
            boxes.Add(boxNr, new Dictionary<string, Lens>());
        }

        var lens = new Lens(label, focalPoint);
        
        boxes[boxNr][label] = lens;
    }
    else
    {
        var label = instruction.Split("-")[0];
        var boxNr = hash(label);

        if (boxes.ContainsKey(boxNr))
        {
            var remainingLenses = boxes[boxNr].Where(x => x.Value.Name != label).ToDictionary(x=> x.Key, x => x.Value);

            boxes[boxNr] = remainingLenses;
        }
    }
}

var total = 0;

foreach (var box in boxes)
{
    Console.Write("Box " + box.Key + ":");

    var position = 1;
    foreach (var lens in box.Value)
    {
        var power = (1 + box.Key) * position * lens.Value.FocalStrength;
        
        Console.Write("[ " + lens.Value.Name + " " + lens.Value.FocalStrength + "]" + power );

        total += power;
        
        position++;
    }
    
    Console.WriteLine();
}
    
Console.WriteLine();
Console.WriteLine();
Console.WriteLine(total);

internal class Lens {
    public readonly string Name;
    public readonly int FocalStrength;

    public Lens(string name, int focalStrength)
    {
        Name = name;
        FocalStrength = focalStrength;
    }
}

