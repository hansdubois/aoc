using System.Net.Http.Headers;
using System.Xml;

namespace ConsoleApp1;

class Program
{
    static async Task Main(string[] args)
    {
        var urls = await File.ReadAllLinesAsync("urls.txt");

        var socketsHandler = new SocketsHttpHandler
        {
            MaxConnectionsPerServer = 10,
            ConnectTimeout = new TimeSpan(0,0,1,0)
        };
        
        HttpClient client = new HttpClient(socketsHandler);

        client.DefaultRequestHeaders.UserAgent.ParseAdd("Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:109.0) Gecko/20100101 Firefox/109.0.");
        
        IEnumerable<Task> tasks = urls.Select(url => Task.Run(async () =>
        {
            try
            {
                Console.WriteLine($"GET : {url}");
                HttpResponseMessage response = await client.GetAsync(url);
                response.EnsureSuccessStatusCode();

                string responseBody = await response.Content.ReadAsStringAsync();
                Console.WriteLine($"{url} --> {response.StatusCode}");
            }
            catch (HttpRequestException e)
            {
                Console.WriteLine($"ERROR {url}: {e.Message}");
            }
            catch (Exception e)
            {
                Console.WriteLine($"INVALID URL {url}: {e.Message}");
            }
        }));
        
        await Task.WhenAll(tasks);
        
        Console.WriteLine($"Press Enter to exit....");
        Console.ReadLine();
    }
}