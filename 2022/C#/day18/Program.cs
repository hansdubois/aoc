using System.Diagnostics.CodeAnalysis;

var cubes = File.ReadAllLines("input/sample.txt").Select((line) =>
{
    var coordinates = line.Split(",").Select(coord => Int32.Parse(coord)).ToList();

    return new Cube(coordinates[0], coordinates[1], coordinates[2]);
}).ToList();


var neighbors = new List<(int x, int y, int z)> { (1, 0, 0), (-1, 0, 0), (0, 1, 0), (0, -1, 0), (0, 0, 1), (0, 0, -1) };

var noNeigbours = 0;
foreach (var cube in cubes)
{
    foreach (var (x, y, z) in neighbors)
    {
        var count = cubes.FindAll((searchCube) =>
        {
            return searchCube.X == cube.X + x && searchCube.Y == cube.Y + y && searchCube.Z == cube.Z + z;
        }).Count;

        if (count == 0)
        {
            noNeigbours++;
        }
    }
}

Console.WriteLine(noNeigbours);

var cubesHashMap  = File.ReadAllLines("input/sample.txt").Select((line) =>
{
    var coordinates = line.Split(",").Select(coord => Int32.Parse(coord)).ToList();
    
    return (x: coordinates[0], y:coordinates[1], z:coordinates[2]);
}).ToHashSet();

var maxX = cubesHashMap.Max(x => x.x);
var maxY = cubesHashMap.Max(y => y.y);
var maxZ = cubesHashMap.Max(z => z.z);
var minX = cubesHashMap.Min(x => x.x);
var minY = cubesHashMap.Min(y => y.y);
var minZ = cubesHashMap.Min(z => z.z);

var leftRight = Enumerable.Range(minX, maxX + 1).ToList();
var bottomTop = Enumerable.Range(minY, maxY + 1).ToList();
var depth = Enumerable.Range(minZ, maxZ + 1).ToList();

// Get cubes on the outside
bool isOnOutside((int x, int y, int z) cube)
{
    if (cubesHashMap.Contains(cube))
        return false;

    var checkedSides = new List<(int x, int y, int z)>();
    var queue = new Queue<(int x, int y, int z)> ();
    
    queue.Enqueue(cube);
    
    while(queue.Any())
    {
        var checkingCube = queue.Dequeue();
        if (checkedSides.Contains(checkingCube)) continue;
        
        checkedSides.Add(checkingCube);
        
        if(!leftRight.Contains(checkingCube.x) || !bottomTop.Contains(checkingCube.y) || !depth.Contains(checkingCube.z))
        {
            // on the outside
            return true;
        }
        
        if(!cubesHashMap.Contains(checkingCube))
        {
            foreach(var (dx, dy, dz) in neighbors)
            {
                queue.Enqueue((checkingCube.x + dx, checkingCube.y + dy, checkingCube.z + dz));
            }
        }
    }
    return false;
}

var onOutside = 0;

foreach(var (x, y, z) in cubesHashMap)
{
    foreach(var (dx, dy, dz) in neighbors)
    {
        if (isOnOutside((x + dx, y + dy, z + dz)))
        {
            onOutside++;
        }
    }
}

Console.WriteLine(onOutside);


class Cube
{
    public int X { get; }
    public int Y { get; }
    public int Z { get; }

    public Cube(int x, int y, int z)
    {
        X = x;
        Y = y;
        Z = z;
    }
}