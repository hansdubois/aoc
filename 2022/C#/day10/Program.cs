var input = File.ReadAllLines("input/sample.txt");

var x = 1;
var signalStrength = 0;
var signalCheckIndex = 20;
var cycle = 0;

var screenWidth = 40;
var pixelPrinted = 0;
var spritePosition = 0;

foreach (var line in input)
{
    IncrementCycle();
    
    var cmd = line.Split(' ');
    switch (cmd[0])
    {
        case "noop": continue;
        case "addx":
        {
            IncrementCycle();
            x += int.Parse(cmd[1]);

            spritePosition = x;
            break;
        }
    }

    void IncrementCycle()
    {
        cycle++;
        
        if (cycle == signalCheckIndex)
        {
            signalStrength += x * cycle;
            signalCheckIndex += 40;
        }
        
        pixelPrinted++;
        
        if (pixelPrinted == spritePosition || pixelPrinted == (spritePosition + 1) || pixelPrinted == (spritePosition + 2))
        {
            Console.Write("#");
        } else {
            Console.Write(".");
        }
        
        
        //Console.Write(".");
        if (pixelPrinted == screenWidth)
        {
           Console.WriteLine();
           pixelPrinted = 0;
        }
    }
}
Console.WriteLine($"Part 1: {signalStrength}");