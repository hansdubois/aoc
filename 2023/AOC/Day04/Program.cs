// See https://aka.ms/new-console-template for more information
var input = File.ReadAllLines("input/input.txt");
var cardPoints = 0.0;

var cardList = new Dictionary<int, int>();
var priceList = new Dictionary<int, int>();

foreach (var line in input)
{
    var split = line.Split(':');
    var gameNumber = Int32.Parse(split[0].Replace("   ", " ").Replace("  ", " ").Split(" ")[1]);
    
    var winningNumbers = split[1].Split('|')[0].Replace("  ", " ").Trim().Split(" ").Select(x => Int32.Parse(x)).ToList();
    var cardNumbers = split[1].Split('|')[1].Replace("  ", " ").Trim().Split(" ").Select(x => Int32.Parse(x)).ToList();

    var matchingNumbers = winningNumbers.Intersect(cardNumbers).Count();
    
    if (matchingNumbers > 0)
    {
        cardPoints += Math.Pow(2, matchingNumbers - 1 );
        //Console.WriteLine(Math.Pow(2, matchingNumbers -1 ));    
    }
    
    cardList.Add(gameNumber, matchingNumbers);
    priceList.Add(gameNumber, 1);
}

foreach (var card in priceList)
{
    for (var times = 0; times < card.Value; times++)
    {
        if (cardList[card.Key] > 0)
        {
            for (var nextKey = card.Key + 1; nextKey < card.Key + cardList[card.Key] + 1; nextKey++)
            {
                priceList[nextKey] += 1;
            }
        }
    }
}


Console.WriteLine("Part 1:" + cardPoints);
Console.WriteLine("Part 2:" + priceList.Values.Sum());

