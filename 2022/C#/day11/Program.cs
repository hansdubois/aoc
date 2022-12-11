using System.ComponentModel.DataAnnotations;
using System.Data;
using System.Text.RegularExpressions;

var monkeysInput = File.ReadAllText("input/sample.txt").Split("\n\n");

var monkeys = new Dictionary<int, Monkey>();

foreach (var monkeyInput in monkeysInput)
{
    var input = monkeyInput.Split("\n");

    var items = input[1].Replace("Starting items: ", "").Split(",").Select(x => Int64.Parse(x)).ToList();
    var operation = input[2].Replace("Operation: new = ", "");
    var decision = Int32.Parse(input[3].Replace("Test: divisible by ", ""));
    var targetTrue = Int32.Parse(input[4].Replace("If true: throw to monkey ", ""));
    var targetFalse = Int32.Parse(input[5].Replace("If false: throw to monkey ", ""));
    
    monkeys.Add(monkeys.Count(), new Monkey(items, operation, decision, targetTrue, targetFalse));
}

for (var i = 0; i < 10000; i++)
{
    Console.WriteLine("Round {0}", i);
    foreach (var monkey in monkeys)
    {  
        Console.WriteLine("Monkey {0}", monkey.Key);
        monkey.Value.inspectItems(monkeys); 
    }
}

var business = monkeys.Select(x => x.Value.inspections).ToList();

Console.WriteLine("Total business: {0}", business[0] * business[1]);

class Monkey
{
    private List<long> _items;
    private readonly string _operation;
    private readonly int _decision;
    private readonly int _targetTrue;
    private readonly int _targetFalse;
    
    public long inspections = 0;

    public Monkey(
        List<long> items,
        string operation,
        int decision,
        int targetTrue,
        int targetFalse
    )
    {
        _items = items;
        _operation = operation;
        _decision = decision;
        _targetTrue = targetTrue;
        _targetFalse = targetFalse;
    }

    public void inspectItems(Dictionary<int, Monkey> monkeys)
    {
        for (var i = 0; i < _items.Count; i++)
        {
            var replacedOperation = _operation.Replace("old", _items[i].ToString());

            Regex regex = new Regex(@"(\d+)\s+([*|+|\-|/])\s+(\d+)");
            var match = regex.Match(replacedOperation);
            
            var h = match.Groups;
            
            var left = Int64.Parse(match.Groups[1].Value);
            var right = Int64.Parse(match.Groups[3].Value);

            long worryLevel = 0; 
            
            switch (match.Groups[2].Value)
            {
                case "*":
                    worryLevel = left * right;
                    break;

                case "+":
                    worryLevel = left + right;
                    break;
            }

            var target = 0;
            worryLevel /= 3;
            
            if (worryLevel % _decision == 0)
            {
                target = _targetTrue;
                monkeys[_targetTrue].catchItem(worryLevel);
            }
            else
            {
                target = _targetFalse;
                monkeys[_targetFalse].catchItem(worryLevel);
            }

            inspections++;
            
            //Console.WriteLine("Inspected: {0}, Worry level: {1}, Target: {2}", _items[i], worryLevel, target);
        }

        _items = new List<long>();
    }
    
    public void catchItem(long item)
    {
        _items.Add(item);
    }
}