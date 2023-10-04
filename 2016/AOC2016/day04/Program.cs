using System.Text.RegularExpressions;

static char Cipher(char ch, int key)
{
	if (!char.IsLetter(ch))
		return ch;

	char offset = char.IsUpper(ch) ? 'A' : 'a';
	return (char)((((ch + key) - offset) % 26) + offset);
}

static string Encipher(string input, int key)
{
	string output = string.Empty;

	foreach (char ch in input)
		output += Cipher(ch, key);

	return output;
}

static string Decipher(string input, int key)
{
	return Encipher(input, 26 - key);
}

var input = File.ReadLines("input/input.txt");
//var regex = new Regex("(?<string>.*)\\[(?<room>(.*))\\]");
var regex = new Regex("^(?<string>[a-zA-Z\\-]+)(?<sector>([\\d]+))\\[(?<key>(.*))\\]");

var counter = 0;

foreach (var line in  input)
{
    var match = regex.Match(line);
    var chars = new Dictionary<string, int>();
    var roomString = match.Groups["string"].ToString().Replace("-", "");

    foreach (var character in roomString)
    {
        var stringChar = character.ToString();
        
        if (!chars.ContainsKey(stringChar))
        {
            chars.Add(stringChar, 0);
        }

        chars[stringChar]++;
    }

    var ordered = chars.OrderByDescending(x => x.Value).ThenBy(x => x.Key);
    var roomKey  = String.Join("", ordered.Take(5).ToDictionary(x => x.Key, x => x.Value).Keys);

    if (string.Compare(roomKey, match.Groups["key"].ToString()) == 0)
    {
        counter = counter + Int32.Parse(match.Groups["sector"].ToString());
    }
    
    var decrypted = Encipher(match.Groups["string"].ToString(), Int32.Parse(match.Groups["sector"].ToString()));
    if (decrypted.Contains("north"))
    {
	    Console.WriteLine(decrypted);
	    Console.WriteLine(match.Groups["sector"]);
    }
}




Console.WriteLine(counter);