<?php

namespace App\Values;

enum MatchupSignificance : string
{
    // single elimination
    case Quarterfinal_1 = 'qf1';
    case Quarterfinal_2 = 'qf2';
    case Quarterfinal_3 = 'qf3';
    case Quarterfinal_4 = 'qf4';
    case Semifinal_1 = 'sf1';
    case Semifinal_2 = 'sf2';
    case Grand_Final = 'f';

    // double elimination
    case Group_A_Opening_Match = 'ao';
    case Group_A_Upper_Bracket_Match = 'aw';
    case Group_A_Lower_Bracket_Match = 'al';
    case Group_A_Deciding_Match = 'ad';

    case Group_B_Opening_Match = 'bo';
    case Group_B_Upper_Bracket_Match = 'bw';
    case Group_B_Lower_Bracket_Match = 'bl';
    case Group_B_Deciding_Match = 'bd';

    case Group_C_Opening_Match = 'co';
    case Group_C_Upper_Bracket_Match = 'cw';
    case Group_C_Lower_Bracket_Match = 'cl';
    case Group_C_Deciding_Match = 'cd';

    case Group_D_Opening_Match = 'do';
    case Group_D_Upper_Bracket_Match = 'dw';
    case Group_D_Lower_Bracket_Match = 'dl';
    case Group_D_Deciding_Match = 'dd';

    // swiss (challengers)
    case Challengers_Stage_Opening_Match = 'c00';

    case Challengers_Stage_Upper_Group_Match = 'c10';
    case Challengers_Stage_Lower_Group_Match = 'c01';

    case Challengers_Stage_Advancing_Match = 'c20';
    case Challengers_Stage_Middle_Group_Match = 'c11';
    case Challengers_Stage_Elimination_Match = 'c02';

    case Challengers_Stage_Second_Advancing_Match = 'c21';
    case Challengers_Stage_Second_Elimination_Match = 'c12';

    case Challengers_Stage_Deciding_Match = 'c22';

    // swiss (legends)
    case Legends_Stage_Opening_Match = 'l00';

    case Legends_Stage_Upper_Group_Match = 'l10';
    case Legends_Stage_Lower_Group_Match = 'l01';

    case Legends_Stage_Advancing_Match = 'l20';
    case Legends_Stage_Middle_Group_Match = 'l11';
    case Legends_Stage_Elimination_Match = 'l02';

    case Legends_Stage_Second_Advancing_Match = 'l21';
    case Legends_Stage_Second_Elimination_Match = 'l12';

    case Legends_Stage_Deciding_Match = 'l22';

    public function getRepresentation() : string
    {
        return match($this) {
            MatchupSignificance::Quarterfinal_1 => 'Quarterfinal 1',
            MatchupSignificance::Quarterfinal_2 => 'Quarterfinal 2',
            MatchupSignificance::Quarterfinal_3 => 'Quarterfinal 3',
            MatchupSignificance::Quarterfinal_4 => 'Quarterfinal 4',
            MatchupSignificance::Semifinal_1 => 'Semifinal 1',
            MatchupSignificance::Semifinal_2 => 'Semifinal 2',
            MatchupSignificance::Grand_Final => 'Grand Final',

            MatchupSignificance::Group_A_Opening_Match => 'Group A Opening Match',
            MatchupSignificance::Group_A_Upper_Bracket_Match => 'Group A Upper Bracket Match',
            MatchupSignificance::Group_A_Lower_Bracket_Match => 'Group A Lower Bracket Match',
            MatchupSignificance::Group_A_Deciding_Match => 'Group A Deciding Match',
            MatchupSignificance::Group_B_Opening_Match => 'Group B Opening Match',
            MatchupSignificance::Group_B_Upper_Bracket_Match => 'Group B Upper Bracket Match',
            MatchupSignificance::Group_B_Lower_Bracket_Match => 'Group B Lower Bracket Match',
            MatchupSignificance::Group_B_Deciding_Match => 'Group B Deciding Match',
            MatchupSignificance::Group_C_Opening_Match => 'Group C Opening Match',
            MatchupSignificance::Group_C_Upper_Bracket_Match => 'Group C Upper Bracket Match',
            MatchupSignificance::Group_C_Lower_Bracket_Match => 'Group C Lower Bracket Match',
            MatchupSignificance::Group_C_Deciding_Match => 'Group C Deciding Match',
            MatchupSignificance::Group_D_Opening_Match => 'Group D Opening Match',
            MatchupSignificance::Group_D_Upper_Bracket_Match => 'Group D Upper Bracket Match',
            MatchupSignificance::Group_D_Lower_Bracket_Match => 'Group D Lower Bracket Match',
            MatchupSignificance::Group_D_Deciding_Match => 'Group D Deciding Match',

            MatchupSignificance::Challengers_Stage_Opening_Match => 'Challengers\' Stage Opening Match',
            MatchupSignificance::Challengers_Stage_Upper_Group_Match => 'Challengers\' Stage Upper Group Match',
            MatchupSignificance::Challengers_Stage_Lower_Group_Match => 'Challengers\' Stage Lower Group Match',
            MatchupSignificance::Challengers_Stage_Advancing_Match => 'Challengers\' Stage Advancing Match',
            MatchupSignificance::Challengers_Stage_Middle_Group_Match => 'Challengers\' Stage Middle Group Match',
            MatchupSignificance::Challengers_Stage_Elimination_Match => 'Challengers\' Stage Elimination Match',
            MatchupSignificance::Challengers_Stage_Second_Advancing_Match => 'Challengers\' Stage Second Advancing Match',
            MatchupSignificance::Challengers_Stage_Second_Elimination_Match => 'Challengers\' Stage Second Elimination Match',
            MatchupSignificance::Challengers_Stage_Deciding_Match => 'Challengers\' Stage Deciding Match',

            MatchupSignificance::Legends_Stage_Opening_Match => 'Legends\' Stage Opening Match',
            MatchupSignificance::Legends_Stage_Upper_Group_Match => 'Legends\' Stage Upper Group Match',
            MatchupSignificance::Legends_Stage_Lower_Group_Match => 'Legends\' Stage Lower Group Match',
            MatchupSignificance::Legends_Stage_Advancing_Match => 'Legends\' Stage Advancing Match',
            MatchupSignificance::Legends_Stage_Middle_Group_Match => 'Legends\' Stage Middle Group Match',
            MatchupSignificance::Legends_Stage_Elimination_Match => 'Legends\' Stage Elimination Match',
            MatchupSignificance::Legends_Stage_Second_Advancing_Match => 'Legends\' Stage Second Advancing Match',
            MatchupSignificance::Legends_Stage_Second_Elimination_Match => 'Legends\' Stage Second Elimination Match',
            MatchupSignificance::Legends_Stage_Deciding_Match => 'Legends\' Stage Deciding Match',
        };
    }
}
