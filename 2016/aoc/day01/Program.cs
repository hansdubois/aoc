using System.Text.RegularExpressions;

var one = new Solution();

System.Console.WriteLine(
one.PartTwo(
    "R2, L5, L4, L5, R4, R1, L4, R5, R3, R1, L1, L1, R4, L4, L1, R4, L4, R4, L3, R5, R4, R1, R3, L1, L1, R1, L2, R5, L4, L3, R1, L2, L2, R192, L3, R5, R48, R5, L2, R76, R4, R2, R1, L1, L5, L1, R185, L5, L1, R5, L4, R1, R3, L4, L3, R1, L5, R4, L4, R4, R5, L3, L1, L2, L4, L3, L4, R2, R2, L3, L5, R2, R5, L1, R1, L3, L5, L3, R4, L4, R3, L1, R5, L3, R2, R4, R2, L1, R3, L1, L3, L5, R4, R5, R2, R2, L5, L3, L1, L1, L5, L2, L3, R3, R3, L3, L4, L5, R2, L1, R1, R3, R4, L2, R1, L1, R3, R3, L4, L2, R5, R5, L1, R4, L5, L5, R1, L5, R4, R2, L1, L4, R1, L1, L1, L5, R3, R4, L2, R1, R2, R1, R1, R3, L5, R1, R4"
    ).ToString());

class Solution {

    public object PartOne(string input) {
        var (irow, icol) = Travel(input).Last();
        return irow + icol;
    }

    public object PartTwo(string input) {
        var seen = new HashSet<(int, int)>();
        foreach (var pos in Travel(input)) {
            if (seen.Contains(pos)) {
                return (pos.icol + pos.irow);
            }
            seen.Add(pos);
        }
        throw new Exception();
    }

    IEnumerable<(int irow, int icol)> Travel(string input) {
        var (irow, icol) = (0, 0);
        var (drow, dcol) = (-1, 0);
        yield return (irow, icol);

        foreach (var stm in Regex.Split(input, ", ")) {
            var d = int.Parse(stm.Substring(1));

            (drow, dcol) = stm[0] switch {
                'R' => (dcol, -drow),
                'L' => (-dcol, drow),
                _ => throw new ArgumentException()
            };
           
            for (int i = 0; i < d; i++) {
                (irow, icol) = (irow + drow, icol + dcol);
                yield return (irow, icol);
            }
        }
    }
}