var races = new List<Race>()
{
    new Race(46, 358),
    new Race(68, 1054),
    new Race(98, 1807),
    new Race(66, 1080),
}.Select(x => x.WaysToBeatRecord()).Aggregate((a, x) => a * x);

Console.WriteLine("Part 1: " + races);
Console.WriteLine("Part 2: " + new Race(46689866, 358105418071080).WaysToBeatRecord());

internal class Race
{
    public long Time { get; }
    public long Distance { get; }
    
    public Race(long time, long distance)
    {
        this.Time = time;
        this.Distance = distance;
    }

    public int WaysToBeatRecord()
    {
        var ways = 0;

        for (var speed = 1; speed < Time; speed++)
        {
            // Does it beat the record within time?
            if (speed * (Time - speed) > Distance)
            {
                ways++;
            }
        }

        return ways;
    }
}