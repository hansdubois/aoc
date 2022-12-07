using System.Collections;

var output = File.ReadAllLines(@"./input/input.txt");

var tree = new Dictionary<string, int>(){{"", 0}};
var folder = "";

foreach (var line in output) {
    if (line.StartsWith("$"))
    {
        if (line.StartsWith("$ cd .."))
        {
            folder = folder.Substring(0, folder.LastIndexOf("/"));
        }
        else if (line.StartsWith("$ cd"))
        {
            folder += "/" + line.Replace("$ cd ", "");

            if (!tree.ContainsKey(folder))
            {
                tree.Add(folder, 0);
            }
        }
    } 
    else if (!line.StartsWith("dir"))
    {
        var fileSize = Int32.Parse(line.Split(" ")[0]);

        tree[folder] += fileSize;
        
        // Calculate up
        var folders = folder.Split("/");

        for (var i = 1; i < folders.Length; i++)
        {
            var folderToAddSizeTo = String.Join("/", folders.Take(i));
            tree[folderToAddSizeTo] += fileSize;
        }
    }
}

var sizeCount = 0;

foreach (var item in tree)
{
    if (item.Value <= 100000)
    {
        sizeCount += item.Value;
    }
    
    Console.WriteLine("Folder {0} space: {1}", item.Key, item.Value);
}

Console.WriteLine(sizeCount);

var totalSpaceInUse = tree[""];
var requiredForUpdate = 30000000;
var TotalOnSystem = 70000000;

var freespaceNeeded = 30000000 - (TotalOnSystem - totalSpaceInUse);

var throwAWAY = 
    tree.Where(f => f.Value >= freespaceNeeded).
        OrderBy(f => f.Value).ToList();

Console.WriteLine("Remove directory {0} with size: {1} {toDelete[0].Value}", throwAWAY[0].Key, throwAWAY[0].Value);