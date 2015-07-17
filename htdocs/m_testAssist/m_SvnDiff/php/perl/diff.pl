use String::Diff;
use String::Diff qw( diff_fully diff diff_merge diff_regexp );# export functions

my $diff = String::Diff::diff($ARGV[0], $ARGV[1],
    remove_open => '<label class="del">',
    remove_close => '</label>',
    append_open => '<label class="ins">',
    append_close => '</label>',
);
print "$diff->[0]$diff->[1]";# this is <del>Perl</del>
