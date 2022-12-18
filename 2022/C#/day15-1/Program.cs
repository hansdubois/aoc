using System.Text.RegularExpressions;

namespace Day15;

class Program
{
    public static void Main(string[] args)
    {
        var regex = new Regex(
            @"Sensor at x=(?<SensorX>-?\d+), y=(?<SensorY>-?\d+): closest beacon is at x=(?<BeaconX>-?\d+), y=(?<BeaconY>-?\d+)");

        var points = File.ReadAllLines("input/input.txt").Select(line => regex.Match(line)).Select((matches) =>
        {
            var beacon = new Coordinate()
                { x = Int32.Parse(matches.Groups["BeaconX"].Value), y = Int32.Parse(matches.Groups["BeaconY"].Value) };
            var sensor = new Coordinate()
                { x = Int32.Parse(matches.Groups["SensorX"].Value), y = Int32.Parse(matches.Groups["SensorY"].Value) };

            var point = new Point()
            {
                beacon = beacon,
                sensor = sensor
            };

            return point;
        }).ToList();
        
        solvePart1(points);
        
        var a = solvePart2(points);
        Console.Write((4000000 * a.x) + a.y);
        
    }
    
    public static void solvePart1(List<Point> points)
    {
         var watch = new System.Diagnostics.Stopwatch();
            watch.Start();
        
            var y_location = 2000000;
            
            var inRange = points.FindAll((point) =>
            {
                return (point.GetDistance() - Math.Abs(y_location - point.sensor.y) >= 0);
            });

            var notPossible = new List<int>();
            var beacons = new List<int>();
            foreach (var point in inRange)
            {
                var distanceToY = point.GetDistance() - Math.Abs(y_location - point.sensor.y);
                
                var leftX = point.sensor.x - distanceToY;
                var rightX = point.sensor.x + distanceToY;

                for (var i = leftX; i <= rightX; i++)
                {
                    notPossible.Add(i);
                }

                if (point.beacon.y == y_location)
                {
                    beacons.Add(point.beacon.y);
                }
                
                Console.WriteLine(
                    "In range x: {0}, y: {1}, distance: {2}, distance to Y: {3}, left: {4}, right {5}", 
                    point.sensor.x, 
                    point.sensor.y, 
                    point.GetDistance(), 
                    distanceToY,
                    leftX,
                    rightX
                );
            }
            
            Console.WriteLine(notPossible.Distinct().ToList().Count - beacons.Distinct().ToList().Count);
            watch.Stop();
            Console.WriteLine($"Execution Time: {watch.ElapsedMilliseconds} ms");
    }

    public static Coordinate solvePart2(List<Point> points)
    {
        var grid = new Dictionary<int, Dictionary<int, string>>();
        
        foreach (var sensor in points)
        {
            var edgesToCheck = sensor.GetEdgeLocations();
            
            foreach (var edge in edgesToCheck)
            {
                if (!isPointInRangeOfAnySensor(edge, points.FindAll((point => point.sensor.x != sensor.sensor.x && point.sensor.y != sensor.sensor.y))))
                { 
                    return edge;
                }   
            }
        }

        return null;
    }
    
 
    private static bool isPointInRangeOfAnySensor(Coordinate pointToCheck, List<Point> allPoints)
    {
        foreach (var point in allPoints)
        {
            if (pointToCheck.x < 0 || pointToCheck.y < 0 || pointToCheck.y > 20 || pointToCheck.x > 20)
            {
                Console.WriteLine($"{pointToCheck.x} {pointToCheck.y}");
                break; // Out of range
            }
                
            if (point.isInSensorRange(pointToCheck))
                return true;
        }

        return false;
    }
}

class Point
{
    public Coordinate sensor { get; set; }
    public Coordinate beacon { get; set; }

    public int GetDistance()
    {
        return Math.Abs(sensor.x - beacon.x) + Math.Abs(sensor.y - beacon.y);
    }
    
    public List<Coordinate> GetEdgeLocations()
    {
        var locations = new List<Coordinate>();

        var top = new Coordinate() {x = sensor.x, y = sensor.y - GetDistance() - 1}; 
        var bottom = new Coordinate() {x = sensor.x, y = sensor.y + GetDistance() + 1};
        var left = new Coordinate() {x = sensor.x - GetDistance(), y = sensor.y - 1};
        var right = new Coordinate() {x = sensor.x + GetDistance(), y = sensor.y + 1};
        
        locations.Add(top);
        locations.Add(bottom);
        locations.Add(left);
        locations.Add(right);
        
        createCoordinatesBetweenPoints(top, right, locations);
        createCoordinatesBetweenPoints(left, top, locations);
        createCoordinatesBetweenPoints(left, bottom, locations);
        createCoordinatesBetweenPoints(bottom, right, locations);

        return locations;
    }

    public bool isInSensorRange(Coordinate pointToCheck)
    {
        return CalculateManhattanDistance(sensor, pointToCheck) <= GetDistance();
    }

    private void createCoordinatesBetweenPoints(Coordinate start, Coordinate end, List<Coordinate> list)
    {
        var (left, right) = start.x <= end.x ? (start, end) : (end, start);
        
        if (left.y <= end.y)
        {
            for (var offset = 0; offset <= right.x - left.x; ++offset)
            {
                list.Add(new Coordinate() {x = left.x + offset, y =left.y + offset});
            }
        }
        else
        {
            for (var offset = 0; offset <= right.x - left.x; ++offset)
            {
                list.Add(new Coordinate() {x = left.x + offset, y = left.y - offset});
            }
        }
    }
    
    public static int CalculateManhattanDistance(Coordinate left, Coordinate right)
    {
        return Math.Abs(left.x - right.x) + Math.Abs(left.y - right.y);
    }

}

class Coordinate
{
    public int x { get; set; }
    public int y { get; set; }
}