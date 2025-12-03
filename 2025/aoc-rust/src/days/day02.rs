use core::include_str;

pub fn run() {
    println!("Running Day 02");
    solve();
}
fn solve() {
    let contents = include_str!("input/day02.txt");
    let mut count = 0;
    let mut count_two = 0;

    let ranges: Vec<(u64, u64)> = contents
        .split(',')
        .map(|row| {
            let mut split = row.split('-');

            (
                split.next().unwrap().parse().unwrap(),
                split.next().unwrap().parse().unwrap(),
            )
        })
        .collect();

    for range in ranges {
        for number in range.0..=range.1 {
            if is_invalid(number) {
                count += number;
            }

            if is_invalid_two(number) {
                count_two += number;
            }
        }
    }

    println!("Part 1: {}", count);
    println!("Part 2: {}", count_two);
}

fn is_invalid(number: u64) -> bool {
    let string = number.to_string();
    let len = string.len();

    // must have even length
    if len.is_multiple_of(2) {
        return false;
    }

    let mid_position = len / 2;
    string[..mid_position] == string[mid_position..]
}

fn is_invalid_two(number: u64) -> bool {
    let string = number.to_string();
    let length = string.len();

    // We only are interested in lengths that can be split
    for part_length in 1..=length / 2 {
        // There should not be a remainder
        if !length.is_multiple_of(part_length) {
            continue;
        }

        // Get the base substring
        let base = &string[..part_length];

        // Construct what the repeated version should look like
        let repeats = length / part_length;
        if repeats < 2 {
            continue; // must repeat at least twice
        }

        // Check if repeating the base gives us the original string
        if base.repeat(repeats) == string {
            return true;
        }
    }

    false
}
