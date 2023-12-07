Dictionary<string, int> valueMapping = new Dictionary<string, int>()
{
    { "A", 14 },
    { "K", 13 },
    { "Q", 12 },
    { "J", 11 },
    { "T", 10 },
    { "9", 9 },
    { "8", 8 },
    { "7", 7 },
    { "6", 6 },
    { "5", 5 },
    { "4", 4 },
    { "3", 3 },
    { "2", 2 },
    { "1", 1 }
};

Dictionary<string, int> valueMappingPart2 = new Dictionary<string, int>()
{
    { "A", 14 },
    { "K", 13 },
    { "Q", 12 },
    { "J", 0 },
    { "T", 10 },
    { "9", 9 },
    { "8", 8 },
    { "7", 7 },
    { "6", 6 },
    { "5", 5 },
    { "4", 4 },
    { "3", 3 },
    { "2", 2 },
    { "1", 1 }
};

var input = File.ReadAllLines("input/sample.txt");
var hands = new List<Hand>();
foreach (var str in input)
{
    var split = str.Split(" ");

    hands.Add(new Hand(split[0], Int32.Parse(split[1])));
}

var sorted = hands.OrderBy(x => (int)x.HandType)
    .ThenByDescending(x => valueMapping[x.cards[0]])
    .ThenByDescending(x => valueMapping[x.cards[1]])
    .ThenByDescending(x => valueMapping[x.cards[2]])
    .ThenByDescending(x => valueMapping[x.cards[3]])
    .ThenByDescending(x => valueMapping[x.cards[4]]);

var sorted2 = hands.OrderBy(x => (int)x.HandType2)
    .ThenByDescending(x => valueMappingPart2[x.cards[0]])
    .ThenByDescending(x => valueMappingPart2[x.cards[1]])
    .ThenByDescending(x => valueMappingPart2[x.cards[2]])
    .ThenByDescending(x => valueMappingPart2[x.cards[3]])
    .ThenByDescending(x => valueMappingPart2[x.cards[4]]);

var currentRank = sorted2.Count();
var total = 0;

foreach (var hand in sorted2)
{
    total += hand.bid * currentRank;

    currentRank--;
}

Console.WriteLine(total);

class Hand
{
    public List<string> cards = new List<string>();
    public HandType HandType { get; }
    public HandType HandType2 { get; }
    
    public string cardString { get; }
    
    public int bid { get; }
    
    
    public Hand(string cards, int bid)
    {
        this.cardString = cards;
        this.cards = cards.ToCharArray().Select(x => x.ToString()).ToList();
        this.HandType = this.classifyHand(this.cards);
        this.HandType2 = this.classifyHandPartTwo(this.cards);
        this.bid = bid;
        
        Console.WriteLine(this.cardString + " " + this.HandType2);
    }
    
    

    private HandType classifyHand(List<string> cards)
    {
        var groups = cards.GroupBy(x => x)
            .ToDictionary(x => x.Key, q => q.Count())
            .ToList()
            .OrderByDescending(x => x.Value);

        if (groups.Count() == 1)
        {
            return HandType.FiveOfAKind;
        }

        if (groups.Count() == 2)
        {
            // Either 4 or Full house;
            if (groups.First().Value == 4)
            {
                return HandType.FourOfAKind;
            }

            return HandType.FullHouse;
        }

        if (groups.Count() == 3)
        {
            // either two pair, three of a kind
            if (groups.First().Value == 3)
            {
                return HandType.ThreeOfAKind;
            }

            return HandType.TwoPair;
        }

        if (groups.Count() > 3)
        {
            if (groups.First().Value == 2)
            {
                return HandType.OnePair;
            }

            return HandType.HighCard;
        }
        
        return HandType.HighCard;
    }
    
    private HandType classifyHandPartTwo(List<string> cards)
    {
        var groups = cards.GroupBy(x => x)
            .ToDictionary(x => x.Key, q => q.Count())
            .ToList()
            .OrderByDescending(x => x.Value);

        var keyValCount = cards.GroupBy(x => x)
            .ToDictionary(x => x.Key, q => q.Count());

        var jokerCount = keyValCount.ContainsKey("J") ? keyValCount["J"] : 0;

        if (groups.Count() == 1)
        {
            return HandType.FiveOfAKind;
        }

        if (groups.Count() == 2)
        {
            // Either 4 or Full house;
            if (jokerCount == 0)
            {
                if (groups.First().Value == 4)
                {
                    return HandType.FourOfAKind;
                }

                return HandType.FullHouse;    
            }

            return HandType.FiveOfAKind;
        }

        if (groups.Count() == 3)
        {
            if (jokerCount == 0)
            {
                // either two pair, three of a kind
                if (groups.First().Value == 3)
                {
                    return HandType.ThreeOfAKind;
                }

                return HandType.TwoPair;    
            }

            if (jokerCount == 1)
            {
                if (groups.First().Value == 3)
                {
                    return HandType.FourOfAKind;
                }

                return HandType.FullHouse;
            }
            
            
            return HandType.FourOfAKind;
        }

        if (groups.Count() > 3)
        {
            if (jokerCount == 0)
            {
                if (groups.First().Value == 2)
                {
                    return HandType.OnePair;
                }

                return HandType.HighCard;    
            }

            if (jokerCount == 1 && groups.First().Value == 2)
            {
                return HandType.ThreeOfAKind;
            }

            if (jokerCount == 2 && groups.First().Value == 2 && groups.First().Key != "J")
            {
                return HandType.FourOfAKind;
            }
            
            if (jokerCount == 2 && groups.First().Value == 2 && groups.First().Key == "J")
            {
                return HandType.ThreeOfAKind;
            }
            
            if (jokerCount == 1 && groups.First().Value == 1)
            {
                return HandType.OnePair;
            }
        }
        
        return HandType.HighCard;
    }
}


enum HandType
{
    FiveOfAKind = 1,
    FourOfAKind = 2,
    FullHouse = 3,
    ThreeOfAKind = 4,
    TwoPair = 5,
    OnePair = 6,
    HighCard = 7
}