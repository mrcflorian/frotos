//
//  CheckboxFriendCell.m
//  Intervention
//
//  Created by Florian Marcu on 3/27/14.
//  Copyright (c) 2014 Florian Marcu. All rights reserved.
//

#import "CheckboxFriendCell.h"

@implementation CheckboxFriendCell

@synthesize imageView;

- (id)initWithStyle:(UITableViewCellStyle)style reuseIdentifier:(NSString *)reuseIdentifier
{
    self = [super initWithStyle:style reuseIdentifier:reuseIdentifier];
    if (self) {
        // Initialization code
    }
    return self;
}

- (void)setSelected:(BOOL)selected animated:(BOOL)animated
{
    [super setSelected:selected animated:animated];

    // Configure the view for the selected state
}

@end
