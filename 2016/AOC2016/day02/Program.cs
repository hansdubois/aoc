// See https://aka.ms/new-console-template for more information
//
var input = File.ReadLines("input/sample.txt");

var keypad = new List<KeyPadKey>()
{
    new KeyPadKey(0, 0, "1"),
    new KeyPadKey(1, 0, "2"),
    new KeyPadKey(2, 0, "3"),
    new KeyPadKey(0, 1, "4"),
    new KeyPadKey(1, 1, "5"),
    new KeyPadKey(2, 1, "6"),
    new KeyPadKey(0, 2, "7"),
    new KeyPadKey(1, 2, "8"),
    new KeyPadKey(2, 2, "9")
};

var bathRoomKeyPad = new List<KeyPadKey>()
{
    new KeyPadKey(2, 0, "1"),
    new KeyPadKey(1, 1, "2"),
    new KeyPadKey(2, 1, "3"),
    new KeyPadKey(3, 1, "4"),
    new KeyPadKey(0, 2, "5"),
    new KeyPadKey(1, 2, "6"),
    new KeyPadKey(2, 2, "7"),
    new KeyPadKey(3, 2, "8"),
    new KeyPadKey(4, 2, "9"),
    new KeyPadKey(1, 3, "A"),
    new KeyPadKey(2, 3, "B"),
    new KeyPadKey(3, 3, "C"),
    new KeyPadKey(2, 4, "D"),
};

var point = new { x = 0, y = 2 };

foreach (var instructionLine in input)
{
    foreach (var instruction in instructionLine.ToCharArray())
    {
        var nextX = 0;
        var nextY = 0;
        
        switch (instruction.ToString())
        {
            case "U":
                nextY = point.y - 1;
                nextX = point.x;    
                break;
            
            case "D":
                nextY = point.y + 1;
                nextX = point.x;    
                break;
            
            case "L":
                nextY = point.y;
                nextX = point.x - 1;    
                break;
            
            case "R":
                nextY = point.y;
                nextX = point.x + 1;    
                break;
        }

        var nextPoint = bathRoomKeyPad.Where(key => key.x == nextX && key.y == nextY);
        
        if (nextPoint.Count() == 1)
        {
            point = new { x = nextX, y = nextY };
        }
    }
    
    var endPoint = bathRoomKeyPad.Where(key => key.x == point.x && key.y == point.y);
    
    Console.WriteLine($"{endPoint.First().value}");
}


public struct KeyPadKey
{
    public readonly int x;
    public readonly int y;
    public readonly string value;

    public KeyPadKey(int x, int y, string value)
    {
        this.value = value;
        this.x = x;
        this.y = y;
    }
}