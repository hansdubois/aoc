using System.Text.RegularExpressions;

namespace Day15;

class Program
{
    public static void Main(string[] args)
    {
        var watch = new System.Diagnostics.Stopwatch();
        watch.Start();

        var regex = new Regex(
            @"Sensor at x=(?<SensorX>-?\d+), y=(?<SensorY>-?\d+): closest beacon is at x=(?<BeaconX>-?\d+), y=(?<BeaconY>-?\d+)");
            
        var y_location = 2000000;

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
    
    
}

class Point
{
    public Coordinate sensor { get; set; }
    public Coordinate beacon { get; set; }

    public int GetDistance()
    {
        return Math.Abs(sensor.x - beacon.x) + Math.Abs(sensor.y - beacon.y);
    }
}

class Coordinate
{
    public int x { get; set; }
    public int y { get; set; }
}