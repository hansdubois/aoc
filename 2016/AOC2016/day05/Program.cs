using System.Security.Cryptography;
using System.Text;

var roomId = "reyedfim";
//findFor(roomId, 8);
partTwo(roomId);

static string findFor(string roomId, int iterations)
{
    
    string foundCharacter = "";
    var index = 0;

    for (var i = 0; i < iterations; i++)
    {
        var found = false;
        while (!found)
        {
            var stringToHash = $"{roomId}{index}";
            //Console.WriteLine(stringToHash);
    
            var md5 = MD5.Create();
            var hash = BitConverter.ToString(md5.ComputeHash(Encoding.UTF8.GetBytes(stringToHash))).Replace("-", "");
    
            if (hash.Substring(0, 5) == "00000")
            {
                foundCharacter = hash.Substring(5, 1);
                found = true;
                
                Console.Write(foundCharacter);
                Console.Write(" ");
                Console.Write(index);
                Console.Write(" ");
                Console.Write(hash);
                Console.WriteLine();
            }
    
            index++;
        }    
    }

    return foundCharacter;
}

static void partTwo(string roomId)
{
    var index = 0;
    var done = false;
    
    var partTwo = new Dictionary<int, string>()
    {
        { 0, "" },
        { 1, "" },
        { 2, "" },
        { 3, "" },
        { 4, "" },
        { 5, "" },
        { 6, "" },
        { 7, "" }
    };

    while (!done)
    {
        var stringToHash = $"{roomId}{index}";
        
        var md5 = MD5.Create();
        var hash = BitConverter.ToString(md5.ComputeHash(Encoding.UTF8.GetBytes(stringToHash))).Replace("-", "");

        if (hash.Substring(0, 5) == "00000")
        {
            int position;
            var isInt = Int32.TryParse(hash.Substring(5, 1), out position);

            if (isInt)
            {   
                var put  = hash.Substring(6, 1);

                if (partTwo.ContainsKey(position) && partTwo[position] == "")
                {
                    partTwo[position] = put;
                }

                foreach (var part in partTwo)
                {
                    if (part.Value == "")
                    {
                        Console.Write(".");
                    }
                    else
                    {
                        Console.Write(part.Value);    
                    }
                }
                
                Console.WriteLine();
            }
        }

        index++;
        done = partTwo.Select((x) => x.Value != "").Count() == 0;
    }
    
    Console.WriteLine(partTwo);
}

