use core::include_str;

pub fn run() {
    println!("Running Day 05");
    solve();
}

#[derive(Ord, Eq, PartialOrd, PartialEq)]
struct Range {
    start: u64,
    end: u64,
}

fn solve() {
    let mut ranges = include_str!("input/day05-ranges.txt")
        .lines()
        .map(|range| {
            let nums: Vec<u64> = range.split('-').map(|x| x.parse().unwrap()).collect();

            Range {
                start: nums[0],
                end: nums[1],
            }
        })
        .collect::<Vec<Range>>();

    ranges.sort_by_key(|range| range.start);

    let mut merged_ranges: Vec<Range> = Vec::new();
    for range in ranges {
        if let Some(last) = merged_ranges.last_mut() {
            if range.start <= last.end + 1 {
                last.end = last.end.max(range.end);
            } else {
                merged_ranges.push(range);
            }
        } else {
            merged_ranges.push(range);
        }
    }

    let mut ingredients = include_str!("input/day05-ingredients.txt")
        .lines()
        .map(|line| line.parse::<u64>().unwrap())
        .collect::<Vec<u64>>();

    let mut fresh_ingredients = 0;
    let mut amount_of_fresh_ids = 0;

    for ingredient in &mut ingredients {
        for range in &merged_ranges {
            if *ingredient >= range.start && *ingredient <= range.end {
                fresh_ingredients += 1;
            }
        }
    }

    for range in &merged_ranges {
        amount_of_fresh_ids += range.end - range.start + 1;
    }

    println!("Fresh ingredients: {}", fresh_ingredients);
    println!("Amount of ids: {}", amount_of_fresh_ids);
}
