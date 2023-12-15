var mode = "sample";
var lines = File.ReadAllLines("input/" + mode + ".txt").ToList();

var counter = 0;

Console.WriteLine(findMirror(lines));

List<string> rotate(List<string> lines)
{
    
}

int findMirror(List<string> lines)
{
    // Horizontal compare
    for (var i = 0; i < lines.Count; i++)
    {
        var linesToCompare = lines.Skip(i).Take(2).ToList();
    
        if (linesToCompare.Count() == 2 && linesToCompare[0] == linesToCompare[1])
        {
            var above = (i + 1);
            var below = lines.Count - (i + 1);
    
            var top = new List<string>();
            var bottom = new List<string>();
    
            if (above > below)
            {
                top = lines.Skip(above - below).Take(below).ToList();
                bottom = lines.Skip(above).Take(below).ToList();
            }
            else
            {
                top = lines.Take(above).ToList();
                bottom = lines.Skip(above).Take(above).ToList();
            }
    
            bottom.Reverse();
    
            foreach (var t in top)
            {
                Console.WriteLine(t);
            }
            
            foreach (var b in bottom)
            {
                Console.WriteLine(b);
            }
    
            var allFine = true;
            
            for (var lineIndex = 0; lineIndex < top.Count; lineIndex++)
            {
                if (top[lineIndex] != bottom[lineIndex])
                {
                    allFine = false;
                    break;
                }
            }
    
            if (allFine)
            {
                return i + 1;
            }
        }
    }
    
    return 0;
}

Console.WriteLine(counter);