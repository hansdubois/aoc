var seedToSoil = getMapping("seedToSoil");
var soilToFertilizer = getMapping("soilToFertilizer");
var fertilizerToWater = getMapping("fertilizerToWater");
var waterToLight = getMapping("waterToLight");
var lightToTemperature = getMapping("lightToTemperature");
var temperatureToHumidity = getMapping("temperatureToHumidity");
var humidityToLocation = getMapping("humidityToLocation");

void part1()
{   
    var seeds = new List<long>()
    {
        41218238,421491713,1255413673,350530906,944138913,251104806,481818804,233571979,2906248740,266447632,3454130719,50644329,1920342932,127779721,2109326496,538709762,3579244700,267233350,4173137165,60179884
    };
    
    var locations = new List<long>();
    
    foreach (var seed in seeds)
    {
        var soil = MapId(seed, seedToSoil);
        var fertilizer = MapId(soil, soilToFertilizer);
        var water = MapId(fertilizer, fertilizerToWater);
        var light = MapId(water, waterToLight);
        var temperature = MapId(light, lightToTemperature);
        var humidity = MapId(temperature, temperatureToHumidity);
        var location = MapId(humidity, humidityToLocation);

        Console.WriteLine("Seed: " + seed + " Has soil : " + soil + " Has fertilizer : " + fertilizer + " Has water: " + water + " Has Light : " + light + " Has Temperature: " + temperature + " Has Humidity: " + humidity + " Has Location: " + location);
    
        locations.Add(location);
    }

    Console.WriteLine("Closed : " + locations.Min());    
}

//part1();


void part2()
{
    var seeds = new Dictionary<long, long>()
    {
        { 79, 14 }, 
        { 55, 13 }
    };

    var real = new Dictionary<long, long>()
    {
        { 41218238, 421491713 },
        { 1255413673, 350530906 },
        { 944138913, 251104806 },
        { 481818804, 233571979 },
        { 2906248740, 266447632 },
        { 3454130719, 50644329 },
        { 1920342932, 127779721 },
        { 2109326496, 538709762 },
        { 3579244700, 267233350 },
        { 4173137165, 60179884 }
    };

    var soilsToCheck = new List<long>();
    
    var locations = new List<long>();
    
    foreach (var seed in real)
    {
        for (var id = seed.Key; id < seed.Key + seed.Value; id++)
        {
            var soil = MapId(id, seedToSoil);
            var fertilizer = MapId(soil, soilToFertilizer);
            var water = MapId(fertilizer, fertilizerToWater);
            var light = MapId(water, waterToLight);
            var temperature = MapId(light, lightToTemperature);
            var humidity = MapId(temperature, temperatureToHumidity);
            var location = MapId(humidity, humidityToLocation);

            Console.WriteLine("Seed: " + seed + " Has soil : " + soil + " Has fertilizer : " + fertilizer + " Has water: " + water + " Has Light : " + light + " Has Temperature: " + temperature + " Has Humidity: " + humidity + " Has Location: " + location);
    
            locations.Add(location);
        }
        
        Console.WriteLine(locations.Min());
    }
    
    Console.WriteLine("Closed : " + locations.Min());   
}

part2();


List<Map> getMapping(string mappingType)
{
    var mapping = new List<Map>();
    var input = File.ReadAllLines("input/input/" + mappingType + ".txt");

    foreach (var line in input)
    {
        var numbers = line.Split(" ").Select(x => Int64.Parse(x)).ToList();
        
        mapping.Add(new Map(
            new Range(numbers[0], numbers[0] + numbers[2] - 1),
            new Range(numbers[1], numbers[1] + numbers[2] - 1)
        ));
    }
    

    return mapping;
}

long MapId (long id, List<Map> maps)
{
    var elegibleMaps = maps.Where(map => map.inRange(id));
    var returnValue = id;

    if (elegibleMaps.Count() == 1)
    {
        returnValue = elegibleMaps.First().map(id);
    }

    return returnValue;
}

List<Range> GetRanges(long start, long end, List<Map> maps)
{
    var ranges = new List<Range>();
    
    foreach (var map in maps)
    {
        if (map.Source.Start <= start && map.Source.End >= end)
        {
            // FULL IN
            ranges.Add(new Range(start, end));
        } else if (map.Source.Start <= start && map.Source.End > end)
        {
            ranges.Add(new Range(start, map.Source.End));
        } else if (map.Source.Start >= start && map.Source.End >= end)
        {
            ranges.Add(new Range(map.Source.Start, end));
        }
    }

    return ranges;
}

class Map
{
    public readonly Range Destination;
    public readonly Range Source;
    
    public Map(Range destination, Range source)
    {
        Destination = destination;
        Source = source;
    }

    public bool inRange(long id)
    {
        return id <= Source.End && id >= Source.Start;
    }
    
    public long map(long id)
    {
        var diffToSource = id - Source.Start;

        return Destination.Start + diffToSource;
    }
}

class Range
{
    public readonly long Start;
    public readonly long End;
    
    public Range(long start, long end)
    {
        Start = start;
        End = end;
    }
}