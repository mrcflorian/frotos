//
//  FacebookInteraction.m
//  Intervention
//
//  Created by Florian Marcu on 3/21/14.
//  Copyright (c) 2014 Florian Marcu. All rights reserved.
//

#import "FacebookInteraction.h"

@implementation FacebookInteraction

+ (void)getMeWithCompletionBlock:(void (^)(FBRequestConnection*, id, NSError*))completionBlock {

    [[self class] sendFacebookRequestWithPath:@"/me" params:nil completionBlock:completionBlock];
}

+ (void)getMyProfilePictureWithCompletionBlock:(void (^)(FBRequestConnection*, id, NSError*))completionBlock {
    NSDictionary *params = [NSDictionary dictionaryWithObjectsAndKeys:
                            @"picture", @"fields", nil
                            ];
    [[self class] sendFacebookRequestWithPath:@"/me" params:params completionBlock:completionBlock];
}

+ (void)getMyFriendsWithCompletionBlock:(void (^)(FBRequestConnection*, id, NSError*))completionBlock {
    [[self class] sendFacebookRequestWithPath:@"/me/friends" params:nil completionBlock:completionBlock];
}

+ (void)getFriendProfilePictureWithUserId:(NSString *)user_id completionBlock:(void (^)(FBRequestConnection*, id, NSError*))completionBlock {
    NSDictionary *params = [NSDictionary dictionaryWithObjectsAndKeys:
                            @"picture", @"fields", nil
                            ];
    NSString *path = [NSString stringWithFormat:@"/%@", user_id];
    [[self class] sendFacebookRequestWithPath:path params:params completionBlock:completionBlock];
}

+ (void)sendFacebookRequestWithPath:(NSString *)graphPath params:(NSDictionary *)params completionBlock:(void (^)(FBRequestConnection*, id, NSError*))completionBlock {
    [FBRequestConnection startWithGraphPath:graphPath
                                 parameters:params
                                 HTTPMethod:@"GET"
                          completionHandler:completionBlock];

}

@end
