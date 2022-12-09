using System.Security.AccessControl;

var actions  = File.ReadAllLines(@"./input/sample.txt");

var dimension = 6;

var head = new Position(0, 0);
var knots = new Dictionary<int, Position>()
{
    {0, new Position(0,0)},
    {1, new Position(0,0)},
    {2, new Position(0,0)},
    {3, new Position(0,0)},
    {4, new Position(0,0)},
    {5, new Position(0,0)},
    {6, new Position(0,0)},
    {7, new Position(0,0)},
    {8, new Position(0,0)},
    {9, new Position(0,0)}
};

var tail = new Position(0, 0);

var places = new List<string>(); 


////////////////////////////////////
// PART 1
////////////////////////////////////
foreach (var action in actions)
{
    var actionParts = action.Split(" ");
    
    var direction = actionParts[0];
    var amount = Int32.Parse(actionParts[1]);
    
    //drawGrid(head, tail);

    for (var i = 0; i < amount; i++)
    {
        head.Move(direction);
        tail.follow(head);

        places.Add($"{tail.x} - {tail.y}");
        
       // draw2Grid(head, tail);
    }
}

////////////////////////////////////
// PART 2
////////////////////////////////////
var part2Locations = new List<string>();

foreach (var action in actions)
{
    var actionParts = action.Split(" ");
    
    var direction = actionParts[0];
    var amount = Int32.Parse(actionParts[1]);

    for (var i = 0; i < amount; i++)
    {
        foreach (var knot in knots)
        {
            if (knot.Key == 0)
            {
                knot.Value.Move(direction);
            }
            else
            {
                knot.Value.follow(knots[knot.Key - 1]);
            }
            
            part2Locations.Add($"{ knot.Value.x} - { knot.Value.y}");    
        }
        // drawKnots(knots);
    }
   
}




Console.WriteLine(places.Distinct().Count());
Console.WriteLine(part2Locations.Distinct().Count());


void draw2Grid(Position head, Position tail)
{
    var dimension = 6;
    
    Console.WriteLine($"{head.x} - {head.y}");
    Console.WriteLine($"{tail.x} - {tail.y}");

    for (var y = dimension -1; y >= 0; y--)
    {
        for (var x = 0 ; x < dimension ; x++)
        {
            if (y == head.y && x == head.x)
            {
                Console.Write("H");
            } else if (y == tail.y && x == tail.x && (tail.x != head.x || tail.y != head.y)) {
                Console.Write("T");
            }
            else
            {
                Console.Write(".");    
            }
        }
        
        Console.Write("\n");
    }
    
    Console.Write("\n");
}

void drawKnots(Dictionary<int, Position> knots)
{
    var dimension = 6;
    
    for (var y = dimension -1; y >= 0; y--)
    {
        for (var x = 0 ; x < dimension ; x++)
        {
            var found = findKnotOnCoord(knots, x, y);
            
            if (found >= 0)
            {
                Console.Write(found);
            }
            else
            {
                Console.Write(".");
            }
        }
        
        Console.Write("\n");
    }
    
    Console.Write("\n");
}

int findKnotOnCoord(Dictionary<int, Position> knots, int x, int y)
{
    foreach (var knot in knots)
    {
        if (knot.Value.x == x && knot.Value.y == y)
        {
            return knot.Key;    
        }
    }
    
    return -1;
}


class Position
{
    public int x;
    public int y;

    public Position(int x, int y)
    {
        this.x = x;
        this.y = y;
    }

    public void Move(string direction)
    {
        switch (direction)
        {
            case "U":
                y += 1;
                break;
            
            case "D":
                y -= 1;
                break;
            
            case "L":
                x -= 1;
                break;
            
            case "R":
                x += 1;
                break;
        }
    }

    private bool amITouching(Position toFollow)
    {
        return (toFollow.x == x && toFollow.y == y || // same position

                toFollow.y - y == 1 && toFollow.x == x || // right above 
                toFollow.y - y == -1 && toFollow.x == x || // right below

                toFollow.x - x == -1 && toFollow.y == y || // left 
                toFollow.x - x == 1 && toFollow.y == y  || // right

                toFollow.x - x == -1 && toFollow.y - y == 1 || // diagonal left top
                toFollow.x - x == 1 && toFollow.y - y == 1 || // diagonal right top

                toFollow.x - x == -1 && toFollow.y - y == -1 || // diagonal left bottom
                toFollow.x - x == 1 && toFollow.y - y == -1 // diagonal right bottom
            );
    }

    public void follow(Position toFollow)
    {
        if (amITouching(toFollow))
            return;
        
        if (toFollow.y - y == 2 && toFollow.x == x)
        {
            // two up
            y += 1;
        } else if (toFollow.y - y == -2 && toFollow.x == x)
        {
            // two down
            y -= 1;
        } else if (toFollow.x - x == 2 && toFollow.y == y )
        {   
            // two right
            x += 1;
        }
        else if (toFollow.x - x == -2 && toFollow.y == y )
        {   
            // two left
            x -= 1;
        } else if (toFollow.x - x == 1 && toFollow.y - y == 2 ||  toFollow.x - x == 2 && toFollow.y - y == 1 || toFollow.x - x == 2 && toFollow.y - y == 2)
        {
            // Diagonal right top
            x += 1;
            y += 1;
        }
        else if (toFollow.x - x == -2 && toFollow.y - y == 1 || toFollow.x - x == -1 && toFollow.y - y == 2 || toFollow.x - x == -2 && toFollow.y - y == 2 )
        {
            // Diagonal left top
            x -= 1;
            y += 1;
        }
        else if (toFollow.x - x == -2 && toFollow.y - y == -1 || toFollow.x - x == -1 && toFollow.y - y == -2 || toFollow.x - x == -2 && toFollow.y - y == -2)
        {
            // Diagonal left
            x -= 1;
            y -= 1;
        }else if (toFollow.x - x == 2 && toFollow.y - y == -1 || toFollow.x - x == 1 && toFollow.y - y == -2 || toFollow.x - x == 2 && toFollow.y - y == -2)
        {
            // Diagonal right bottom
            x += 1;
            y -= 1;
        } 
    }
}