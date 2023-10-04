var lines = File.ReadAllLines("input/sample.txt");

var Assignments = new Dictionary<string, string>();
var Solved = new Dictionary<string, long>();

foreach (var line in lines)
{
    var monkey = line.Split(":")[0];
    var assignment = line.Substring(line.IndexOf(":")).Replace(": ", "");
    
    long number;
    if (Int64.TryParse(assignment, out number))
    {
        Solved.Add(monkey, number);  
    }
    else
    {
        Assignments.Add(monkey, assignment);
    }
}

while (Assignments.Count > 0)
{
    foreach (var assignment in Assignments)
    {
        var parts = assignment.Value.Split(" ");

        if (Solved.ContainsKey(parts[0]) && Solved.ContainsKey(parts[2]))
        {
            var left = Solved[parts[0]];
            var right = Solved[parts[2]];
            
            switch (parts[1])
            {
                case "*":
                    Solved.Add(assignment.Key, left * right);
                    break;
                
                case "/":
                    Solved.Add(assignment.Key, left / right);
                    break;
                
                case "+":
                    Solved.Add(assignment.Key, left + right);
                    break;
                
                case "-":
                    Solved.Add(assignment.Key, left - right);
                    break;
            }

            Assignments.Remove(assignment.Key);
        }
    }
}

Console.WriteLine("Done , {0}", Solved["root"]);