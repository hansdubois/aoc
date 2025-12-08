use core::include_str;

pub fn run() {
    println!("Running Day 07");
    solve();
}
fn solve() {
    let mut beams: Vec<u64> = Vec::new();
    let mut lines = include_str!("input/day07.txt").lines();

    let mut splitCount = 0;
    let mut beamcount = 0;

    let startPosition = lines.next().unwrap().find('S') ;
    beams.push(startPosition.unwrap() as u64);

    lines.for_each(|line| {
        if line.contains("^") {
            let mut newBeams: Vec<u64> = Vec::new();

            let positions: Vec<u64> =
                line.match_indices('^')
                    .map(|(i, _)| i as u64)
                    .collect();

            for beam in &beams {
                if positions.contains(&beam) {
                    if !newBeams.contains(&(*&beam - 1)) || !newBeams.contains(&(*&beam + 1)) {
                        splitCount += 1;
                    }

                    if !newBeams.contains(&(*&beam - 1)) {
                        newBeams.push(*&beam - 1);
                    }

                    if !newBeams.contains(&(*&beam + 1)) {
                        newBeams.push(*&beam + 1);
                    }
                } else {
                    newBeams.push(**&beam);
                }
            }

            if beams.len() != newBeams.len() {
                beamcount += newBeams.len() as i32;
            }


            beams = newBeams;
        }

        for (i, ch) in line.chars().enumerate() {
            if beams.contains(&(i as u64)) {
                print!("|");
            } else {
                print!("{}", ch);
            }
        }

        println!()
    });

    println!("Part 1: {}", splitCount);
    println!("Part 2: {}", beamcount);
}
